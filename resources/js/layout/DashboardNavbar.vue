<template>
  <base-nav
    :label="pageTitle"
    class="navbar-top navbar-dark"
    id="navbar-main"
    :show-toggle-button="false"
    expand
  >
    <form
      class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto"
    >
      <div class="form-group mb-0">
        <base-input
          placeholder="Search"
          class="input-group-alternative"
          alternative=""
          addon-right-icon="fas fa-search"
        >
        </base-input>
      </div>
    </form>
    <ul v-if="admin" class="navbar-nav align-items-center d-none d-md-flex">
      <li class="nav-item dropdown">
        <base-dropdown class="nav-link pr-0">
          <div class="media align-items-center" slot="title">
            <span class="avatar avatar-sm rounded-circle">
              <img alt="Image placeholder" src="/img/user.png" />
            </span>
            <div class="media-body ml-2 d-none d-lg-block">
              <span class="mb-0 text-sm font-weight-bold">{{
                admin.name
              }}</span>
            </div>
          </div>

          <template>
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <inertia-link href="/admin/profile" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </inertia-link>
            <div class="dropdown-divider"></div>
            <inertia-link
              href="/admin/logout"
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
      admin: null,
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
    this.admin = this.$page.props.admin;
  },
};
</script>
