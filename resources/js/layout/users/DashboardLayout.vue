<template>
  <div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
    <side-bar
      :background-color="sidebarBackground"
      short-title="DHOCP"
      title="DHOCP"
      :urls="{
        main: '/',
        profile: '/profile',
        logout: '/logout',
      }"
    >
      <template slot="links">
        <sidebar-item
          :link="{
            name: 'Dashboard',
            icon: 'ni ni-tv-2 text-primary',
            path: '/',
          }"
        />
        <sidebar-item
          :link="{
            name: 'Data entry',
            icon: 'ni ni-bullet-list-67 text-success',
            path: '/modules',
          }"
        />
        <sidebar-item
          :link="{
            name: 'Progress',
            icon: 'ni ni-chart-bar-32 text-blue',
            path: '/progress',
          }"
        />
        <sidebar-item
          :link="{
            name: 'Inbox',
            icon: 'ni ni-email-83 text-green',
            path: '/inbox',
          }"
          :badge="{
            count: unread,
          }"
        />
      </template>
    </side-bar>
    <div class="main-content" :data="sidebarBackground">
      <dashboard-navbar></dashboard-navbar>

      <div @click="toggleSidebar">
        <fade-transition :duration="200" origin="center top" mode="out-in">
          <!-- your content here -->
          <slot />
        </fade-transition>
        <content-footer></content-footer>
      </div>
    </div>
  </div>
</template>
<script>
import DashboardNavbar from "./DashboardNavbar.vue";
import ContentFooter from "./ContentFooter.vue";
import { FadeTransition } from "vue2-transitions";
import { mapGetters } from "vuex";

export default {
  components: {
    DashboardNavbar,
    ContentFooter,
    FadeTransition,
  },
  data() {
    return {
      sidebarBackground: "vue", //vue|blue|orange|green|red|primary
    };
  },
  methods: {
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    },
    getUnreadMessageCount() {
      const token = this.$page.props.auth_user.data.api_token;

      if (token) {
        axios
          .get(`/api/getUnreadMessageCount?api_token=${token}`)
          .then((resp) => {
            this.$store.dispatch("fetchUnreadCount", resp.data);
          })
          .catch((err) => {
            console.error("Unable to load unread messages!");
          });
      }
    },
  },
  computed: {
    ...mapGetters(["unread"]),
  },
  created() {
    this.getUnreadMessageCount();
  },
};
</script>
<style lang="scss">
</style>
