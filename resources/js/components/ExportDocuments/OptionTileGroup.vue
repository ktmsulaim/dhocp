<template>
  <div class="row">
    <div v-for="option in options" :key="option.value" class="col-md-6 mb-3">
      <div
        class="option-tile border rounded p-3 h-100"
        :class="{ 'option-tile--active': option.value === value }"
        @click="select(option.value)"
      >
        <div class="custom-control custom-radio">
          <input
            :id="inputId(option.value)"
            type="radio"
            class="custom-control-input"
            :name="groupName"
            :value="option.value"
            :checked="option.value === value"
            @change="select(option.value)"
          />
          <label
            :for="inputId(option.value)"
            class="custom-control-label option-tile__label"
          >
            {{ option.label }}
            <span class="d-block small text-muted font-weight-normal mt-1">
              <template v-if="codeHint">
                e.g. <code class="option-tile__code">{{ option.hint }}</code>
              </template>
              <template v-else>{{ option.hint }}</template>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { randomString } from "../stringUtils";

export default {
  name: "option-tile-group",
  props: {
    options: {
      type: Array,
      default: () => [],
    },
    value: {
      type: [String, Number],
      default: null,
    },
    codeHint: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      groupName: `option-tile-${randomString()}`,
    };
  },
  methods: {
    inputId(optionValue) {
      return `${this.groupName}-${optionValue}`;
    },
    select(optionValue) {
      if (optionValue !== this.value) this.$emit("input", optionValue);
    },
  },
};
</script>

<style scoped>
.option-tile {
  cursor: pointer;
  transition: border-color 0.15s ease, background-color 0.15s ease;
}
.option-tile:hover {
  border-color: #adb5bd !important;
}
.option-tile--active,
.option-tile--active:hover {
  border-color: #5e72e4 !important;
  background-color: rgba(94, 114, 228, 0.05);
}
.option-tile__label {
  cursor: pointer;
  color: #32325d;
}
.option-tile--active .option-tile__label {
  color: #5e72e4;
  font-weight: 600;
}
.option-tile__code {
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  background-color: #f6f9fc;
  color: #525f7f;
  font-size: 0.75rem;
}
</style>
