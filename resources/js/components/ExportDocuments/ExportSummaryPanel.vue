<template>
  <div class="card border shadow-none">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0">Summary</h5>
      <small class="d-block text-muted">Review your selection before downloading</small>
    </div>
    <div class="card-body">
      <div
        v-for="row in rows"
        :key="row.label"
        class="d-flex justify-content-between py-2 border-bottom"
      >
        <span class="text-muted text-sm mr-3">{{ row.label }}</span>
        <span class="font-weight-600 text-sm text-right">{{ row.value }}</span>
      </div>
      <base-button
        type="primary"
        icon="ni ni-cloud-download-95"
        class="mt-4"
        block
        :loading="loading"
        :disabled="disabled"
        @click="$emit('submit')"
      >
        Download ZIP
      </base-button>
      <small v-if="loading && !notice" class="text-muted d-block mt-2 text-center">
        Checking files and preparing your download...
      </small>
      <div v-if="error" class="alert alert-danger py-2 px-3 mt-3 mb-0 text-sm">
        {{ error }}
      </div>
      <div v-if="notice" class="alert alert-success py-2 px-3 mt-3 mb-0 text-sm">
        {{ notice }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "export-summary-panel",
  props: {
    studentsLabel: {
      type: String,
      default: "",
    },
    documentsLabel: {
      type: String,
      default: "",
    },
    groupingLabel: {
      type: String,
      default: "",
    },
    namingLabel: {
      type: String,
      default: "",
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    notice: {
      type: String,
      default: null,
    },
    error: {
      type: String,
      default: null,
    },
  },
  computed: {
    rows() {
      return [
        { label: "Students", value: this.studentsLabel },
        { label: "Documents", value: this.documentsLabel },
        { label: "Folder structure", value: this.groupingLabel },
        { label: "File naming", value: this.namingLabel },
      ];
    },
  },
};
</script>
