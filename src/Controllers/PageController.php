<?php

namespace Wearenext\CMS\Controllers;

use Illuminate\Http\Request;
use Wearenext\CMS\Models\PageType;
use Wearenext\CMS\Support\Html\Form;

class PageController extends BaseController
{
    public function index(Request $request)
    {
        $this->authorize('cms.page_index');
        
        $type = PageType::all()->first();
        if (!is_null($type)) {
            return redirect()->to($type->pageUrl());
        }
        return redirect()
            ->route('cms.pagetype.create');
    }
    
    public function view(Request $request, $type)
    {
        $this->authorize('cms.page_view');
        
        $query = $type->pages();
        if (!empty($searchQuery = $request->get('q', ''))) {
            $query->where('name', 'LIKE', "%{$searchQuery}%");
        }
        return view('cms::page.view')
            ->with('type', $type)
            ->with('pages', $query->paginate());
    }
    
    public function create(Request $request, $type)
    {
        $this->authorize('cms.page_create');
        
        $form = new Form;
        return view('cms::page.create')
            ->with('type', $type)
            ->with('form', $form);
    }
    
    public function edit(Request $request, $type, $page)
    {
        $this->authorize('cms.page_edit');
        
        $form = new Form($page->toArray());
        return view('cms::page.edit')
            ->with('type', $type)
            ->with('page', $page)
            ->with('form', $form);
    }

    public function save(Request $request, $type)
    {
        $this->authorize('cms.page_create');
        
        $this->validate($request, [
            'name' => 'required|string|between:1,255',
            'meta_title' => 'string|between:1,255',
            'meta_description' => 'string|between:1,255',
            'paths' => 'array',
        ]);

        $attributes = $request->all();
        
        $attributes['features'] = $type->features;

        $page = $type->pages()->create($attributes);

        $this->fillFeatures(array_keys($type->features), $page, $attributes);

        $this->paths(array_values($request->get('paths', [])), $page);

        return redirect()
            ->to($page->blockUrl())
            ->withErrors([ 'success' => [ trans('cms::page.messages.saved', [ 'name' => $page->name, ]) ] ]);
    }
    
    public function update(Request $request, $type, $page)
    {
        $this->authorize('cms.page_edit');
        
        $this->validate($request, [
            'name' => 'required|string|between:1,255',
            'meta_title' => 'string|between:1,255',
            'meta_description' => 'string|between:1,255',
            'paths' => 'array',
        ]);

        $attributes = $request->all();
        
        $attributes['features'] = $type->features;

        $page->fill($attributes);

        $this->fillFeatures(array_keys($type->features), $page, $attributes);

        $this->paths(array_values($request->get('paths', [])), $page);

        $page->save();

        return redirect()
            ->to($request->has('next')?$page->blockUrl():$type->pageUrl())
            ->withErrors([ 'success' => [ trans('cms::page.messages.updated', [ 'name' => $page->name, ]) ] ]);
    }
    
    public function delete(Request $request, $type, $page)
    {
        $this->authorize('cms.page_delete');
        
        $page->delete();
        return redirect()
            ->to($type->pageUrl())
            ->withErrors([ 'success' => [ trans('cms::page.messages.deleted', [ 'name' => $page->name, ]) ] ]);
    }

    public function publish($type, $page)
    {
        $this->authorize('cms.page_publish');
        
        $page->published = true;
        $page->save();

        $name = $page->name;
        $preview = $page->previewUrl();

        return redirect()
            ->to($type->pageUrl())
            ->withErrors([ 'success' => [ trans('cms::page.messages.published', compact('name', 'preview')), ] ]);
    }

    public function unpublish($type, $page)
    {
        $this->authorize('cms.page_unpublish');
        
        $page->published = false;
        $page->save();

        $name = $page->name;
        $preview = $page->previewUrl();

        return redirect()
            ->to($type->pageUrl())
            ->withErrors([ 'success' => [ trans('cms::page.messages.unpublished', compact('name', 'preview')), ] ]);
    }

    protected function fillFeatures($features, $page, $attributes)
    {
        foreach ($features as $feature) {
            $model = config("cms.features.{$feature}");
            if (class_exists($model)) {
                $m = new $model();
                $q = $m->whereHas('page', function ($q) use ($page) {
                    return $q->where('id', $page->id);
                });

                $instance = $q->first();

                if (is_null($instance)) {
                    $m->fill($attributes);
                    $m->save();

                    $m->page()->associate($page);
                    $m->save();
                } else {
                    $instance->fill($attributes);
                    $instance->save();
                }
            }
        }
    }

    protected function paths($paths, $page)
    {
        $page->urls()->delete();

        foreach ($paths as $path) {
            $path = trim($path);
            if (empty($path)) {
                continue;
            }
            $page->urls()->create(['url' => $path,]);
        }
    }
}