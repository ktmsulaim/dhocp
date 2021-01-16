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
                  <h3 class="mb-0">Add item to {{ module.name }}</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    @click="backToModule"
                    type="primary"
                    icon="ni ni-bold-left"
                    >Back to {{ module.name }}</base-button
                  >
                </div>
              </div>
            </div>

            <div class="container my-3">
              <div class="row">
                <div class="col-md-12">
                  <base-input
                    alternative
                    placeholder="Enter title here"
                    label="Label"
                    v-model="form.label"
                  >
                  </base-input>
                  <p v-if="form.errors.label" class="small text-danger">
                    {{ form.errors.label }}
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label class="form-control-label">Description</label>
                    <textarea
                      class="form-control"
                      v-model="form.description"
                      placeholder="Enter description here..."
                    ></textarea>
                    <p
                      v-if="form.errors.description"
                      class="small text-danger mt-2"
                    >
                      {{ form.errors.description }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <base-input
                    v-model="form.placeholder"
                    label="Placeholder"
                    placeholder="Additional info"
                  ></base-input>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Required</label>
                    <select v-model="form.required" class="form-control">
                      <option value="1">Required</option>
                      <option value="0">Not required</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label"
                      >Size (Medium device)</label
                    >
                    <select class="form-control" v-model="form.size">
                      <option value="1">Full</option>
                      <option value="2">Half</option>
                      <option value="3">One third</option>
                      <option value="4">Quarter</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <base-input
                    label="Order"
                    type="number"
                    min="1"
                    v-model="form.order"
                    placeholder="Enter lower value to see first"
                  ></base-input>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Type</label>
                    <select class="form-control" v-model="form.type">
                      <option value="text">Text</option>
                      <option value="number">Number</option>
                      <option value="textarea">Text area</option>
                      <option value="dropdown">Dropdown</option>
                      <option value="date">Date</option>
                      <option value="file">File</option>
                      <option value="checkbox">Checkbox</option>
                    </select>
                    <p v-if="form.errors.type" class="small text-danger mt-2">
                      {{ form.errors.type }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <additional-value :type="form.type"></additional-value>

            <div class="my-3">
              <div class="container">
                <div class="row">
                  <div class="col">
                    <base-button
                      :loading="form.processing"
                      :disabled="form.processing"
                      type="primary"
                      @click="submit"
                      >Submit</base-button
                    >
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
import DashboardLayout from "../../../../layout/DashboardLayout";
import AdditionalValue from "./AdditionalValue";

import { mapGetters } from "vuex";

export default {
  layout: DashboardLayout,
  components: {
    AdditionalValue,
  },
  props: ["module"],
  data() {
    return {
      notification: {
        status: false,
        type: "info",
        message: null,
        head: "Info",
      },
      form: this.$inertia.form({
        module_id: this.module.id,
        key: null,
        label: null,
        description: null,
        required: 1,
        type: null,
        placeholder: null,
        additional: {},
        size: null,
        order: null,
      }),
    };
  },
  computed: {
    ...mapGetters(["options"]),
  },
  methods: {
    backToModule() {
      this.$inertia.get(
        this.$route("modules.show", { module: this.module.id })
      );
    },
    isValid(key) {
      if (this.form[key] && this.form.errors[key] == null) {
        return true;
      } else {
        return false;
      }
    },
    slug(str) {
      if (str) {
        return str.toLowerCase().split(" ").join("-");
      }
    },
    submit() {
      this.form
        .transform((data) => {
          if (data.label) {
            data.key = this.slug(data.label);
          }

          if (this.options && this.options.length > 0) {
            data.additional = this.options;
          }

          return data;
        })
        .post(this.$route("items.store"));
    },
  },
  destroyed() {
    this.$store.dispatch("fetchOptions", []);
  },
};
</script>

<style>
</style>