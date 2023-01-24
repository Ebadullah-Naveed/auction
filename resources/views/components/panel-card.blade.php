<div @if(!empty($attributes['class'])) {{ $attributes->class(['']) }} @else {{ $attributes->class(['col-md-12']) }} @endif >
    <div class="card">
        <div class="card-header white">

            @if( isset($filterBtn) && ($filterBtn=='show') )
                {{-- When enale filter btn in panel card please add these classes (collapse & filter_collapse_box) and Id (filterCollapseBox) in filters row div --}}
                <div class="d-flex justify-content-between align-items-center">
                    <strong> {{$title}} </strong>
                    <div>
                        <a class="filter_refresh_btn mr-2" href="javascript:void(0)" type="button" > <i class="icon icon-refresh"></i> Refresh </a>
                        <a class="filter_collapse_btn" data-toggle="collapse" href="#filterCollapseBox" role="button" aria-expanded="false" aria-controls="filterCollapseBox"> <i class="icon icon-filter"></i> Filters </a>
                    </div>
                </div>
            @else
                <strong> {{$title}} </strong>
            @endif

        </div>
        <div class="card-body text-centerx">
            
            {{$slot}}
            
        </div>

        @if( isset($footer) )
            <div class="card-footer white">
                {{$footer}}
            </div>
        @endif

    </div>
</div>