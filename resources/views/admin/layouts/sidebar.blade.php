<div class="side-nav">
    <div class="side-nav-inner">
        <div class="admin-sidebar-brand">
            @if(data_get($infor, 'logo'))
                <img class="admin-sidebar-logo" src="{{ asset(data_get($infor, 'logo')) }}" alt="{{ data_get($infor, 'name', 'Website') }}">
            @else
                <span class="admin-sidebar-brand-name">{{ data_get($infor, 'name', 'Website') }}</span>
            @endif
        </div>
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open">
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
                    <li class="{{ Request::is('admin/case-study') || Request::is('admin/case-study/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.case-study') }}">Case Study</a>
                    </li>
                    <li class="{{ Request::is('admin/category') || Request::is('admin/category/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.category') }}">Chuyên mục</a>
                    </li>
                    <li class="{{ Request::is('admin/course') || Request::is('admin/course/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.course') }}">Khóa học</a>
                    </li>
                    <li class="{{ Request::is('admin/lead') || Request::is('admin/lead/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.lead') }}">Leads</a>
                    </li>
                    <li class="{{ Request::is('admin/subscriber') || Request::is('admin/subscriber/*') ? 'active' : '' }}">
                        <a href="{{ route('admin.subscriber') }}">Đăng ký email</a>
                    </li>
                    <li class="{{ Request::is('admin/lead-magnet') || Request::is('admin/lead-magnet/*') ? 'active' : '' }}"><a href="{{ route('admin.lead-magnet') }}">Tài liệu tặng</a></li>
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
