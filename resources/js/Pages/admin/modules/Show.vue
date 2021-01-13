<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row mb-3" v-if="notification.status">
                <div class="col">
                  <base-alert :type="notification.type">
                    <strong>{{ notification.head }}!</strong>
                    {{ notification.message }}
                  </base-alert>
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">{{ module.name }}</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="backToModules"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to modules</base-button
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["module"],
  data() {
    return {
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
    };
  },
  methods: {
    backToModules() {
      this.$inertia.get(this.$route("modules.index"));
    },
  },
};
</script>

<style>
</style>