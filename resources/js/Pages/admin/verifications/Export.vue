<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Export</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="$inertia.get($route('verifications.index'))"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to verifications</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <h4>Verifications</h4>
              <div class="row">
                <div class="col-md-6">
                  <div v-if="verifications && verifications.length > 0">
                    <form ref="form" :action="$route('verifications.export.post')" method="post">
                        <input type="hidden" name="_token" v-model="token">
                        <input type="hidden" name="verifications[]" v-model="selected">
                        <base-checkbox
                      :multiple="true"
                      v-for="verification in verifications"
                      :key="verification.id"
                      class="custom-control-alternative"
                    >
                      <span @click="addToItems(verification.id)">{{
                        verification.name
                      }}</span>
                    </base-checkbox>

                    <div class="mt-3">
                  <base-button
                    :loading="loading"
                    :disabled="loading"
                    @click="submitForm"
                    >Submit</base-button>
                </div>
                    </form>
                  </div>
                  <div v-else>
                    <p>No verifications found!</p>
                  </div>
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
  props: ["verifications"],
  data() {
    return {
      loading: false,
      selected: [],
      token: $('meta[name="csrf-token"]')[0].content,
    };
  },
  methods: {
    addToItems(verificationId) {
      if (verificationId) {
        if(this.selected.includes(verificationId)) {
            this.selected.splice(this.selected.indexOf(verificationId), 1);
        } else {
            this.selected.push(verificationId);
        }
      }
    },
    submitForm() {
      if (this.selected && this.selected.length == 0) {
        return;
      }

      this.loading = true;

      this.$refs.form.submit();

      this.loading = false;
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Export verifications");
  },
};
</script>

<style>
</style>