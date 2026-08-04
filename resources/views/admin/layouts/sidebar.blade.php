<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Website</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="{{ Request::is('admin/profile') ? 'active' : '' }}">
                        <a href="{{ route('admin.profile') }}">Thông tin cá nhân</a>
                    </li>
                    <li class="{{ Request::is('admin/image') ? 'active' : '' }}">
                        <a href="{{ route('admin.image') }}">Hình ảnh</a>
                    </li>
                    <li class="{{ Request::is('admin/skill') ? 'active' : '' }}">
                        <a href="{{ route('admin.skill') }}">Kỹ năng</a>
                    </li>
                    <li class="{{ Request::is('admin/service') ? 'active' : '' }}">
                        <a href="{{ route('admin.service') }}">Ngành nghề</a>
                    </li>
                    <li class="{{ Request::is('admin/blog') ? 'active' : '' }}">
                        <a href="{{ route('admin.blog') }}">Bài viết</a>
                    </li>
                    <li class="{{ Request::is('admin/course') || Request::is('admin/course/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.course') }}">Khóa học</a>
                    </li>
                    <li class="{{ Request::is('admin/lead') || Request::is('admin/lead/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.lead') }}">Leads</a>
                    </li>
                    <li class="{{ Request::is('admin/testimonial') || Request::is('admin/testimonial/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.testimonial') }}">Testimonial</a>
                    </li>
                    <li class="{{ Request::is('admin/setting') ? 'active' : '' }}">
                        <a href="{{ route('setting') }}">Cài đặt</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
