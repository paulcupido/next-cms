<div class="l-section">
    <div class="l-content">
        <label class="{{ $form->field('label')->labelClass() }}" for="label">
            @lang('cms::pagetype.fields.label.label')
            <input class="input" type="text" id="label" name="{{ $form->field('label')->name() }}" maxlength="255" placeholder="@lang('cms::pagetype.fields.label.placeholder')" value="{{ $form->field('label')->value() }}" />
            {!! $form->field('label')->helpHtml() !!}
        </label>
        <label class="{{ $form->field('slug')->labelClass() }}" for="slug">
            @lang('cms::pagetype.fields.slug.label')
            <input class="input" type="text" id="slug" name="{{ $form->field('slug')->name() }}" maxlength="255" placeholder="@lang('cms::pagetype.fields.slug.placeholder')" value="{{ $form->field('slug')->value() }}" />
            {!! $form->field('slug')->helpHtml() !!}
        </label>
    </div>
</div>
<div class="l-section">
    <div class="l-content">
        <h4>@lang('cms::pagetype.fields.fields.label')</h4>
        @foreach ($form->field('fields')->value([]) as $key => $value)
        <div class="row">
            <div class="col col-1-2">
                <label class="{{ $form->field("fields.{$key}.field")->labelClass() }}" for="field-{{ $key }}">
                    <span class="u-visuallyhidden">@lang('cms::pagetype.fields.fields.label')</span>
                    <input class="input" type="text" id="field-{{ $key }}" name="fields[{{ $key }}][field]" maxlength="50" placeholder="@lang('cms::pagetype.fields.fields.placeholder')" value="{{ $value['field'] }}" />
                    {!! $form->field("fields.{$key}.field")->helpHtml() !!}
                </label>
            </div>
            <div class="col col-1-2">
                <label class="{{ $form->field("fields.{$key}.value")->labelClass() }}" for="value-{{ $key }}">
                    <span class="u-visuallyhidden">@lang('cms::pagetype.fields.value.label')</span>
                    <input class="input" type="text" id="value-{{ $key }}" name="fields[{{ $key }}][value]" maxlength="50" placeholder="@lang('cms::pagetype.fields.value.placeholder')" value="{{ $value['value'] }}" />
                    {!! $form->field("fields.{$key}.value")->helpHtml() !!}
                </label>
            </div>
        </div>
        @endforeach
        <div class="row js-fields-entry" data-clonekey="{{ count($form->field('fields')->value([])) }}">
            <div class="col col-1-2">
                <label class="label" for="field-new-id">
                    <span class="u-visuallyhidden">@lang('cms::pagetype.fields.fields.label')</span>
                    <input class="input" type="text" id="field-new-id" name="fields[id][field]" maxlength="50" placeholder="@lang('cms::pagetype.fields.fields.placeholder')" value="" />
                </label>
            </div>
            <div class="col col-1-2">
                <label class="label" for="value-new-id">
                    <span class="u-visuallyhidden">@lang('cms::pagetype.fields.value.label')</span>
                    <input class="input" type="text" id="value-new-id" name="fields[id][value]" maxlength="50" placeholder="@lang('cms::pagetype.fields.value.placeholder')" value="" />
                </label>
            </div>
        </div>

        <button class="btn btn--bordered btn--icon btn--small js-clone-entry-add" type="button" data-clone="js-fields-entry"><span class="icon icon--left fa fa-plus" title="Add" aria-hidden="true"></span>Label</button>
    </div>
</div>
<div class="l-section">
    <div class="l-content">
        <fieldset class="fieldset fieldset--bordered">
            <legend class="legend">@lang('cms::pagetype.fields.features.label')</legend>
            @foreach (array_keys(config('cms.features', [])) as $key => $feature)
            <label class="label label--checkbox label--inline" for="feature-{{$key}}">
                <input type="checkbox" class="js-feature-checkbox" id="feature-{{$key}}" name="features[{{ $feature }}]" value="1" data-feature="{{ $feature }}"{{ $form->field('features')->checked($feature) }} /> @lang("cms::pagetype.features.{$feature}")
            </label>
            @endforeach
            <input type="hidden" name="features[id]" value="" />
            <input type="hidden" name="blocks[id]" value="" />
        </fieldset>

        <br />

        <fieldset class="fieldset fieldset--bordered options_page_hero">
            <legend class="legend">@lang('cms::pagetype.fields.page_hero.label')</legend>
            <label class="label label--checkbox label--inline" for="page_hero_buttons">
                <input type="radio" id="page_hero_buttons" name="_features_page_buttons" value="1" data-applies-to="page_hero"{{ $form->field('features.page_hero')->checked('1') }} /> @lang('cms::pagetype.fields.page_hero.hero_buttons')
            </label>
            <label class="label label--checkbox label--inline" for="page_hero_no_buttons">
                <input type="radio" id="page_hero_no_buttons" name="_features_page_buttons" value="no_buttons" data-applies-to="page_hero"{{ $form->field('features.page_hero')->checked('no_buttons') }} /> @lang('cms::pagetype.fields.page_hero.no_buttons')
            </label>
        </fieldset>

        <br />

        <fieldset class="fieldset fieldset--bordered options_page_relationship">
            <legend class="legend">@lang('cms::pagetype.fields.page_relationship.label')</legend>
            <div class="row">
                <div class="col col-1-2">
                    <label class="{{ $form->field('relations.0.label')->labelClass() }}" for="relationship-name">
                        <span class="u-visuallyhidden">@lang('cms::pagetype.fields.page_relationship_name.label')</span>
                        <input class="input" type="text" id="relationship-name" name="relations[0][label]" maxlength="50" placeholder="@lang('cms::pagetype.fields.page_relationship_name.placeholder')" value="{{ $form->field('relations.0.label')->value() }}" />
                        {!! $form->field('relationship.name')->helpHtml() !!}
                    </label>
                </div>
                <div class="col col-1-2">
                    <label class="label" for="relationship-page">
                        <span class="u-visuallyhidden">@lang('cms::pagetype.fields.page_relationship_related.label')</span>
                        <select id="relationship-page" name="relations[0][pagetype_id]" class="input input--select">
                            @foreach (Wearenext\CMS\Models\PageType::all() as $entry)
                            <option value="{{ $entry->id }}"{!! $form->field('relations.0.pagetype_id')->selected($entry->id) !!}>{{ $entry->label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<div class="l-section">
    <div class="l-content">
        <fieldset class="fieldset fieldset--bordered">
            <legend class="legend">@lang('cms::pagetype.fields.blocktypes.label')</legend>
            <label class="label label--checkbox label--inline" for="block-callout">
                <input type="checkbox" id="block-callout" name="blocks[call_out]" value="1"{!! $form->field('blocks.call_out')->checked() !!}/> @lang('cms::pagetype.fields.blocktypes.call_out')
            </label>
            <label class="label label--checkbox label--inline" for="block-icon-list">
                <input type="checkbox" id="block-icon-list" name="blocks[icon_list]" value="1"{!! $form->field('blocks.icon_list')->checked() !!}/> @lang('cms::pagetype.fields.blocktypes.icon_list')
            </label>
            <label class="label label--checkbox label--inline" for="block-media-html">
                <input type="checkbox" id="block-media-html" name="blocks[media_html]" value="1"{!! $form->field('blocks.media_html')->checked() !!}/> @lang('cms::pagetype.fields.blocktypes.media_html')
            </label>
            <label class="label label--checkbox label--inline" for="block-media-image">
                <input type="checkbox" id="block-media-image" name="blocks[media_image]" value="1"{!! $form->field('blocks.media_image')->checked() !!}/> @lang('cms::pagetype.fields.blocktypes.media_image')
            </label>
            <label class="label label--checkbox label--inline" for="block-text">
                <input type="checkbox" id="block-text" name="blocks[text]" value="1"{!! $form->field('blocks.text')->checked() !!}/> @lang('cms::pagetype.fields.blocktypes.text')
            </label>
        </fieldset>
    </div>
</div>