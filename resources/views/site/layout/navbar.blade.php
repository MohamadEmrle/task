<nav class="navbar navbar-fixed-top">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                    <span class="fa fa-bars"></span>
                </button>

                <a href="index.html" class="navbar-brand hidden-sm hidden-md hidden-lg"><img src="images/logo.png" alt="LOGO"></a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right text-align-left">
                    <li class="active"><a href="{{ route('site.index') }}">الرئيسية</a></li>
                    <li><a href="{{ route('site.abouts') }}">من نحن</a></li>
                    <li><a href="{{ route('site.index') }}">خدماتنا</a></li>
                </ul>

                <a href="index.html" class="navbar-brand hidden-xs text-center"><img src="{{ asset('site/images/logo.png') }}" alt="LOGO"></a>

                <ul class="nav navbar-nav navbar-left text-align-right">
                    <li><a href="{{ route('site.categories') }}">معرض الصور</a></li>
                    <li><a href="{{ route('site.contact') }}">اتصل بنا</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
