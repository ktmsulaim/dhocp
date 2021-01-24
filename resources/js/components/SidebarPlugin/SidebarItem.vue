<template>
  <li class="nav-item">
    <inertia-link
      @click="linkClick"
      class="nav-link d-flex justify-content-between"
      :target="link.target"
      :href="link.path"
    >
      <template>
        <div class="icLabel">
          <i :class="link.icon"></i>
          <span class="nav-link-text">{{ link.name }}</span>
        </div>
        <badge v-if="badge && badge.count > 0" type="danger">{{
          badge.count
        }}</badge>
      </template>
    </inertia-link>
  </li>
</template>
<script>
export default {
  name: "sidebar-item",
  props: {
    link: {
      type: Object,
      default: () => {
        return {
          name: "",
          path: "",
          children: [],
        };
      },
      description:
        "Sidebar link. Can contain name, path, icon and other attributes. See examples for more info",
    },
    badge: {
      type: Object,
    },
  },
  inject: {
    autoClose: {
      default: true,
    },
  },
  data() {
    return {
      children: [],
      collapsed: true,
    };
  },
  methods: {
    linkClick() {
      if (
        this.autoClose &&
        this.$sidebar &&
        this.$sidebar.showSidebar === true
      ) {
        this.$sidebar.displaySidebar(false);
      }
    },
  },
};
</script>

<style scoped>
.icLabel i {
  margin-right: 15px;
}
</style>
