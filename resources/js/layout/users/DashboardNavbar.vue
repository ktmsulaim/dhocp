<template>
  <base-nav
    :label="pageTitle"
    class="navbar-top navbar-dark"
    id="navbar-main"
    :show-toggle-button="false"
    expand
  >
    <ul v-if="user" class="navbar-nav align-items-center d-none d-md-flex">
      <li class="nav-item dropdown">
        <base-dropdown class="nav-link pr-0">
          <div class="media align-items-center" slot="title">
            <span class="avatar avatar-sm rounded-circle overflow-hidden">
              <img alt="Image placeholder" :src="user.profile" />
            </span>
            <div class="media-body ml-2 d-none d-lg-block">
              <span class="mb-0 text-sm font-weight-bold">{{ user.name }}</span>
            </div>
          </div>

          <template>
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <inertia-link href="/profile" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </inertia-link>
            <div class="dropdown-divider"></div>
            <inertia-link
              href="/logout"
              method="post"
              class="dropdown-item"
              as="button"
            >
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </inertia-link>
          </template>
        </base-dropdown>
      </li>
    </ul>
  </base-nav>
</template>
<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      activeNotifications: false,
      showMenu: false,
      searchQuery: "",
      user: null,
    };
  },
  methods: {
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    },
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
  },
  computed: {
    ...mapGetters(["pageTitle"]),
  },
  created() {
    this.user = this.$page.props.auth_user.data;
  },
};
</script>
