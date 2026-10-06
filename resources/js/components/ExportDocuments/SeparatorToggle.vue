<template>
  <div
    class="d-flex flex-wrap align-items-center justify-content-between border-top pt-3"
  >
    <label class="form-control-label mb-2 mb-sm-0 mr-3">Separator</label>
    <div class="btn-group btn-group-sm" role="group" aria-label="Separator">
      <button
        v-for="opt in options"
        :key="opt.value"
        type="button"
        class="btn btn-outline-primary"
        :class="{ active: opt.value === value }"
        :aria-pressed="opt.value === value ? 'true' : 'false'"
        @click="$emit('input', opt.value)"
      >
        {{ opt.label }}
      </button>
    </div>
  </div>
</template>

<script>
import { SEPARATOR_OPTIONS } from "./exportOptions";

export default {
  name: "separator-toggle",
  props: {
    value: {
      type: String,
      default: "underscore",
    },
  },
  data() {
    return {
      options: SEPARATOR_OPTIONS,
    };
  },
};
</script>
