<template>
  <div>
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3>Add new record</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="
                      $inertia.get(
                        $route('user.modules.show', { module: module.id })
                      )
                    "
                    >Back to {{ module.name }}</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row" v-if="module.items && module.items.length > 0">
                <div
                  v-for="item in module.items"
                  :key="item.id"
                  :class="getInputSize(item)"
                >
                  <single-item :item="item"></single-item>
                </div>
              </div>
              <div v-else>
                <p>No data! Stay tuned!</p>
              </div>
            </div>
            <div
              class="card-footer"
              v-if="module.items && module.items.length > 0"
            >
              <base-button @click="submit" type="primary">Submit</base-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/users/DashboardLayout";
import SingleItem from "../../../components/SingleItem";

import { mapGetter, mapGetters } from "vuex";

export default {
  props: ["module", "items"],
  layout: DashboardLayout,
  components: {
    SingleItem,
  },
  data() {
    return {
      form: {
        module_id: this.module.id,
        data: {},
      },
    };
  },
  computed: {
    ...mapGetters(["getItemValue"]),
  },
  methods: {
    getInputSize(item) {
      if (item.size) {
        const size = item.size;

        if (size == 1) {
          return "col-sm-12 col-md-12";
        } else if (size == 2) {
          return "col-sm-12 col-md-6";
        } else if (size == 3) {
          return "col-sm-12 col-md-4";
        } else if (size == 4) {
          return "col-sm-12 col-md-3";
        } else {
          return "";
        }
      }
    },
    submit() {
      if (this.getItemValue) {
        this.form.data = this.getItemValue;
      }
      this.$inertia.post(this.$route("user.modules.createItems"), this.form, {
        onSuccess: (page) => {},
      });
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Add new record | " + this.module.name);
  },
};
</script>

<style>
</style>