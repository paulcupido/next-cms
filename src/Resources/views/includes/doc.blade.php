<div class="card is-published">
    <div class="card-inner">
        <a class="card__link" href="{{ route('cms.doc.view', compact('id')) }}" target="_blank">
            <h3 class="card__title">{{ $doc->file_name }}</h3>
            <p class="card__synopsis"></p>
            <time class="card__time" datetime="{{ $doc->updated_at->format('Y-m-d') }}">@lang('cms::messages.last_updated', [ 'date' => $doc->updated_at->format('j F Y, H:i') ])</time>
        </a>
        <a class="btn btn--bordered btn--small" href="{{ route('cms.doc.delete', compact('id', 'hash')) }}" role="button">@lang('cms::controls.delete')</a>
    </div>
    <div class="card-inner document" id={{ "document-".$doc->id }}>
        <form action="{{ route('cms.doc.add_to_fund_page') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" value="{{$doc->id}}" name="document_id"/>
            @foreach($pageProducts as $pp)
                <label class="label">
                    Page: {{ array_get($pp, 'page_name') }}
                    <br />
                    Section: {{ array_get($pp, 'product_name') }}
                    <input value="{{$doc->id}}" class="input" type="checkbox" name="fund_pages[{{array_get($pp, 'id')}}]" @if($doc->id === $pp->document_id) checked @endif>
                </label>
            @endforeach
            <label class="label">
                <input class="btn" type="submit" name="submit" value="Submit" />
            </label>
        </form>
    </div>
</div>
