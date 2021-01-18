<li class="{{ Request::is('admin') ? 'active' : '' }}">
   <a href="{{ route('admin.beranda.index') }}">
      <i class="fa fa-th-large"></i><span class="nav-label">Beranda</span>
   </a>
</li>

<li class="{{ Request::is('admin/profil') ? 'active' : '' }}">
   <a href="{{ route('profil') }}">
      <i class="fa fa-user"></i><span class="nav-label">Profil</span>
   </a>
</li>

<li class="{{ Request::is('admin/materi') ? 'active' : '' }}">
   <a href="{{ route('admin.user.index') }}">
      <i class="fa fa-cubes"></i><span class="nav-label">Materi</span>
   </a>
</li>

<li class="{{ Request::is('admin/user') ? 'active' : '' }}">
   <a href="{{ route('admin.user.index') }}">
      <i class="fa fa-database"></i><span class="nav-label">Data User</span>
   </a>
</li>