<template>
  <div class="card border shadow-none mb-4">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0">Archive preview</h5>
      <small class="d-block text-muted">
        Example structure based on your settings
      </small>
    </div>
    <div class="card-body">
      <pre class="archive-preview"><span
        v-for="(line, index) in lines"
        :key="index"
        class="d-block"
        :class="{ 'font-weight-bold': index === 0 }"
      >{{ line }}</span></pre>
      <small class="d-block text-muted mt-3">
        Duplicate file names are numbered automatically (e.g. _1).
      </small>
    </div>
  </div>
</template>

<script>
import { buildPreviewTree } from "./previewTree";

export default {
  name: "archive-preview",
  props: {
    groupBy: {
      type: String,
      required: true,
    },
    namingFormat: {
      type: String,
      required: true,
    },
    separator: {
      type: String,
      required: true,
    },
    includePhoto: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    lines() {
      const tree = buildPreviewTree({
        groupBy: this.groupBy,
        namingFormat: this.namingFormat,
        separator: this.separator,
        includePhoto: this.includePhoto,
      });
      return ["documents.zip", ...tree];
    },
  },
};
</script>

<style scoped>
.archive-preview {
  max-height: 280px;
  margin: 0;
  padding: 1rem;
  overflow: auto;
  border: 1px solid #e9ecef;
  border-radius: 0.375rem;
  background-color: #f6f9fc;
  color: #525f7f;
  font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Courier New", monospace;
  font-size: 0.8125rem;
  line-height: 1.6;
}
</style>
