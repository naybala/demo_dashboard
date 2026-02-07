<div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
    <style>
    .activeNav {
        background-color: #bcc8d7;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    .sidebar-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .sidebar-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .multi-menu-container li a {
        padding-left: 20px;
        /* Increased padding to accommodate icons */
        position: relative;
        display: flex;
        align-items: center;
    }

    .multi-menu-container {
        position: relative;
    }

    .multi-menu-container li {
        position: relative;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }

    .multi-menu-container li::before {
        font-family: "Font Awesome 5 Free";
        content: "\f111";
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        font-size: 6px;
        color: #999;
        z-index: 2;
        font-weight: 900;
    }

    .multi-menu-container li::after {
        content: "";
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 1px;
        background-color: #999;
        transform: translateX(0);
    }

    /* Remove vertical line for last child */
    .multi-menu-container li:last-child::after {
        height: 50%;
    }

    /* Remove vertical line for first child */
    .multi-menu-container li:first-child::after {
        top: 50%;
    }
    </style>

    <aside class="z-20 w-64 overflow-y-hidden bg-gray-50 border-r dark:bg-gray-800 flex-shrink-0 asideShowHide"
        id="asideShowHide">
        <div class=" text-gray-500 dark:text-gray-400 ">
            <img src="{{ asset('images/logo.jpg') }}" />
            <ul class="overflow-y-hidden hover:overflow-y-auto aside sidebar-scrollbar">
                <li
                    class="flex flex-col justify-end items-center mx-2 menu-title pb-4 border-y border-gray-700/25 gap-2">
                    <x-theme.adjustTheme />
                    <x-localization.lang />
                </li>
                {{-- Start Dashboard --}}
                <li class="{{ request()->routeIs('dashboard.index') ? 'bg-secondary text-white' : '' }} my-1">
                    <a href="{{ route('dashboard.index') }}"
                        class="flex items-center ps-4 py-2 font-normal hover:bg-secondary hover:text-white dark:rounded-none">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="ml-3 text-sm menu-title">{{ __('sidebar.dashboard') }}</span>
                    </a>
                </li>
                <x-sidebar.list title="sidebar.user" model="users" icon="fa-solid fa-user" />
                <x-sidebar.list title="sidebar.role" model="roles" icon="fa-solid fa-lock" />
                <x-sidebar.list title="sidebar.category" icon="fa-solid fa-book" model="categories" />
                <x-sidebar.list title="sidebar.product" icon="fa-solid fa-book" model="products" />
                <x-sidebar.list title="sidebar.audit" icon="fa-solid fa-book" model="audits" />
                <x-sidebar.list title="sidebar.unit" icon="fa-solid fa-book" model="units" />
                <!-- <x-sidebar.multi-menu dropdownName="sidebar_article" menuName="sidebar.article"
                    :menuLists="['categories']" menuIcon="fa-solid fa-book">
                                    <x-sidebar.list title="sidebar.category" model="categories" />

                </x-sidebar.multi-menu> -->
            </ul>
        </div>
       
    </aside>
</div>
