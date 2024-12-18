<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { 
  LayoutDashboard, 
  Menu as MenuIcon, 
  Package, 
  BarChart3, 
  User, 
  LogOut, 
  ChevronDown 
} from 'lucide-vue-next';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);

// Color scheme
const colors = {
  primary: 'bg-indigo-600',
  secondary: 'bg-indigo-500',
  text: 'text-white',
  hover: 'hover:bg-indigo-700',
};
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <nav :class="[colors.primary, 'border-b border-indigo-400']">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <div class="flex">
              <!-- Logo -->
              <div class="flex shrink-0 items-center">
                  <img src="../../../storage/app/public/uploads/icons8-vegan-food-50 (1).png" class="block h-9 w-auto fill-current text-white">
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                  :class="[colors.text, colors.hover]"
                >
                  <LayoutDashboard class="w-5 h-5 mr-2" />
                  Dashboard
                </NavLink>

                <NavLink
                  :href="route('menu')"
                  :active="route().current('menu')"
                  :class="[colors.text, colors.hover]"
                >
                  <MenuIcon class="w-5 h-5 mr-2" />
                  Menu
                </NavLink>

                <NavLink
                  :href="route('inventory')"
                  :active="route().current('inventory')"
                  :class="[colors.text, colors.hover]"
                >
                  <Package class="w-5 h-5 mr-2" />
                  Inventory
                </NavLink>

                <NavLink
                  :href="route('analytics')"
                  :active="route().current('analytics')"
                  :class="[colors.text, colors.hover]"
                >
                  <BarChart3 class="w-5 h-5 mr-2" />
                  Analytics
                </NavLink>
              </div>
            </div>

            <div class="hidden sm:ms-6 sm:flex sm:items-center">
              <!-- Settings Dropdown -->
              <div class="relative ms-3">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        :class="[colors.secondary, colors.text, colors.hover, 'inline-flex items-center rounded-md px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none']"
                      >
                        {{ $page.props.auth.user.name }}
                        <ChevronDown class="ms-2 h-4 w-4" />
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <DropdownLink :href="route('profile.edit')">
                      <User class="w-4 h-4 mr-2" />
                      Profile
                    </DropdownLink>
                    <DropdownLink :href="route('logout')" method="post" as="button">
                      <LogOut class="w-4 h-4 mr-2" />
                      Log Out
                    </DropdownLink>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                :class="[colors.text, colors.hover, 'inline-flex items-center justify-center rounded-md p-2 transition duration-150 ease-in-out focus:outline-none']"
              >
                <MenuIcon v-if="!showingNavigationDropdown" class="h-6 w-6" />
                <svg v-else class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden"
        >
          <div class="space-y-1 pb-3 pt-2">
            <ResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
              :class="[colors.text, colors.hover]"
            >
              <LayoutDashboard class="w-5 h-5 mr-2" />
              Dashboard
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('menu')"
              :active="route().current('menu')"
              :class="[colors.text, colors.hover]"
            >
              <MenuIcon class="w-5 h-5 mr-2" />
              Menu
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('inventory')"
              :active="route().current('inventory')"
              :class="[colors.text, colors.hover]"
            >
              <Package class="w-5 h-5 mr-2" />
              Inventory
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('analytics')"
              :active="route().current('analytics')"
              :class="[colors.text, colors.hover]"
            >
              <BarChart3 class="w-5 h-5 mr-2" />
              Analytics
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div :class="[colors.secondary, 'border-t border-indigo-400 pb-1 pt-4']">
            <div class="px-4">
              <div class="text-base font-medium text-white">
                {{ $page.props.auth.user.name }}
              </div>
              <div class="text-sm font-medium text-indigo-200">
                {{ $page.props.auth.user.email }}
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('profile.edit')" :class="[colors.text, colors.hover]">
                <User class="w-5 h-5 mr-2" />
                Profile
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('logout')" method="post" as="button" :class="[colors.text, colors.hover]">
                <LogOut class="w-5 h-5 mr-2" />
                Log Out
              </ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>