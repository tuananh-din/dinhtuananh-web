<div class="header">
    <div class="nav-wrap">
        <ul class="nav-left">
            <li class="desktop-toggle">
                <button type="button" class="admin-header-icon" aria-label="Thu gọn hoặc mở rộng thanh điều hướng">
                    <i class="anticon" aria-hidden="true"></i>
                </button>
            </li>
            <li class="mobile-toggle">
                <button type="button" class="admin-header-icon" aria-label="Mở hoặc đóng thanh điều hướng">
                    <i class="anticon" aria-hidden="true"></i>
                </button>
            </li>
        </ul>
        <ul class="nav-right">
            <li class="dropdown dropdown-animated scale-left">
                <button id="admin-profile-toggle" type="button" class="admin-profile-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-controls="admin-profile-menu">
                    <i class="anticon anticon-user" aria-hidden="true"></i>
                    <span>{{ data_get(auth()->user(), 'name', 'Tài khoản quản trị') }}</span>
                    <i class="anticon anticon-down admin-profile-toggle__chevron" aria-hidden="true"></i>
                </button>
                <div id="admin-profile-menu" class="dropdown-menu dropdown-menu-right pop-profile admin-profile-menu" aria-labelledby="admin-profile-toggle">
                    <p class="admin-profile-menu__label">Tài khoản</p>
                    <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center p-h-15 p-v-10">
                        <i class="anticon anticon-user" aria-hidden="true"></i>
                        <span class="m-l-10">Hồ sơ</span>
                    </a>
                    <a href="{{ route('setting') }}" class="dropdown-item d-flex align-items-center p-h-15 p-v-10">
                        <i class="anticon anticon-setting" aria-hidden="true"></i>
                        <span class="m-l-10">Cài đặt website</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center p-h-15 p-v-10 admin-profile-menu__logout">
                            <i class="anticon anticon-logout" aria-hidden="true"></i>
                            <span class="m-l-10">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
