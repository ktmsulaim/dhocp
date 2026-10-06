<template>
  <div class="card border shadow-none mb-4">
    <div
      class="card-header bg-white py-3 d-flex align-items-center justify-content-between"
    >
      <div class="d-flex align-items-center">
        <span class="export-step mr-3">{{ step }}</span>
        <div>
          <h5 class="mb-0">{{ title }}</h5>
          <small v-if="subtitle" class="d-block text-muted">{{ subtitle }}</small>
        </div>
      </div>
      <div v-if="$scopedSlots.actions" class="ml-3 text-nowrap">
        <slot name="actions"></slot>
      </div>
    </div>
    <div class="card-body">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "export-section",
  props: {
    step: {
      type: Number,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: "",
    },
  },
};
</script>

<style scoped>
.export-step {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: #5e72e4;
  color: #fff;
  font-size: 0.75rem;
  font-weight: 600;
}
</style>
