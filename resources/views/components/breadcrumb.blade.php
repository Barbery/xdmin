<ul id="breadcrumb" class="breadcrumb">
    <li><i class="ace-icon fa fa-home home-icon"></i><a href="/">首页</a></li>
    @if(isset($breadcrumbs) && is_array($breadcrumbs))
        @foreach ($breadcrumbs as $name => $uri)
            @if(empty($uri))
                <li>{{$name}}</li>
            @else
                <li><a href="{{$uri}}">{{$name}}</a></li>
            @endif
        @endforeach
    @endif
</ul>
