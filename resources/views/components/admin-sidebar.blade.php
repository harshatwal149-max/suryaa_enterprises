
@php
    $isCatalogActive = request()->routeIs('admin.categories.*')
        || request()->routeIs('admin.subcategories.*');
@endphp

<div class="sidebar-menu">
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    <div class="sidebar-group {{ $isCatalogActive ? 'open' : '' }}">
        <button type="button"
                class="sidebar-link sidebar-toggle {{ $isCatalogActive ? 'active' : '' }}"
                onclick="this.parentElement.classList.toggle('open')">
            <span>Catalog</span>
            <span>⌄</span>
        </button>

        <div class="sidebar-submenu">
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                Categories
            </a>

            <a href="{{ route('admin.subcategories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                Subcategories
            </a>
        </div>
    </div>
</div>

<style>
    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 12px 16px;
        color: inherit;
        text-decoration: none;
        background: transparent;
        border: none;
        border-radius: 8px;
        text-align: left;
        cursor: pointer;
        font: inherit;
        box-sizing: border-box;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background: rgba(128, 128, 128, 0.15);
    }

    .sidebar-submenu {
        display: none;
        padding-left: 14px;
    }

    .sidebar-group.open .sidebar-submenu {
        display: block;
    }
</style>
