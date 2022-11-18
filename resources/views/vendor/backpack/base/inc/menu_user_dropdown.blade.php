<li class="nav-item dropdown pr-4">
  <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="position: relative;height: 35px;margin: 0 10px;">
    @if (backpack_user()->Profile)
      <img src="{{ URL(backpack_user()->Profile) }}" style="width: 35px;height: 35px;border-radius: 50%;">
    @else
      <span class="backpack-avatar-menu-container bg-primary" style="width: 35px;height: 35px;border-radius: 50%;color: #FFF;line-height: 35px;font-size: 85%;font-weight: 300;">
        {{backpack_user()->getAttribute('name') ? mb_substr(backpack_user()->name, 0, 1, 'UTF-8') : 'A'}}
      </span>
    @endif
    
    <span class="ml-2 text-white">{{ backpack_user()->getAttribute('name') }}</span>
    <i class="la la-caret-down text-white ml-2"></i>
  </a>
  <div class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1">
    <a class="dropdown-item" href="{{ route('backpack.account.info') }}"><i class="la la-user"></i> {{ trans('backpack::base.my_account') }}</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ backpack_url('logout') }}"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</a>
  </div>
</li>
