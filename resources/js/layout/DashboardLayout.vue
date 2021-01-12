<template>
  <div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
    <side-bar
      :background-color="sidebarBackground"
      short-title="Argon"
      title="Argon"
    >
      <template slot="links">
        <sidebar-item
          :link="{
            name: 'Dashboard',
            icon: 'ni ni-tv-2 text-primary',
            path: '/admin/',
          }"
        />

        <sidebar-item
          :link="{
            name: 'Batches',
            icon: 'ni ni-bold text-blue',
            path: '/admin/batches',
          }"
        />
        <sidebar-item
          :link="{
            name: 'Students',
            icon: 'ni ni-hat-3 text-orange',
            path: '/admin/students',
          }"
        />
        <sidebar-item
          :link="{
            name: 'Modules',
            icon: 'ni ni-ungroup text-yellow',
            path: '/admin/modules',
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
  computed: {
    ...mapGetters(["admin"]),
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
  },
  created() {
    if (!this.admin) {
      this.$store.dispatch("fetchAdmin");
    }
  },
};
</script>
<style lang="scss">
</style>
