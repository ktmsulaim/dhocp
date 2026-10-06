<template>
  <export-section
    :step="step"
    title="Documents & photos"
    subtitle="Choose the files to include in the archive"
  >
    <template v-slot:actions>
      <button
        type="button"
        class="btn btn-link btn-sm text-primary p-0"
        @click="setAll(true)"
      >
        Select all
      </button>
      <span class="text-light mx-2">|</span>
      <button
        type="button"
        class="btn btn-link btn-sm text-muted p-0"
        @click="setAll(false)"
      >
        Clear
      </button>
    </template>
    <div
      class="border rounded px-3 py-2 d-flex justify-content-between align-items-center mb-3"
    >
      <base-checkbox
        :checked="includePhoto ? '1' : '0'"
        class="custom-control-alternative"
        @input="$emit('update:includePhoto', isChecked($event))"
      >
        Profile photos
      </base-checkbox>
      <span class="badge badge-pill badge-primary">
        {{ totalPhotos }} available
      </span>
    </div>
    <template v-if="modules && modules.length > 0">
      <document-module-group
        v-for="module in modules"
        :key="module.id"
        :module="module"
        :selected-items="selectedItems"
        @update:selectedItems="$emit('update:selectedItems', $event)"
      />
    </template>
    <p v-else class="text-muted text-sm mb-0">No document fields found.</p>
  </export-section>
</template>

<script>
import ExportSection from "./ExportSection";
import DocumentModuleGroup from "./DocumentModuleGroup";
import { collectItemIds } from "./sourceItems";
import { isChecked } from "./checkboxUtils";

export default {
  name: "document-source-list",
  components: {
    ExportSection,
    DocumentModuleGroup,
  },
  props: {
    step: {
      type: Number,
      default: 2,
    },
    modules: {
      type: Array,
      default: () => [],
    },
    includePhoto: {
      type: Boolean,
      default: false,
    },
    selectedItems: {
      type: Array,
      default: () => [],
    },
    totalPhotos: {
      type: Number,
      default: 0,
    },
  },
  methods: {
    isChecked,
    setAll(checked) {
      this.$emit("update:includePhoto", checked);
      this.$emit("update:selectedItems", checked ? collectItemIds(this.modules) : []);
    },
  },
};
</script>
