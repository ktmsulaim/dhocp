<template>
  <div
    class="custom-control custom-checkbox"
    :class="{ disabled: disabled, 'form-check-inline': inline }"
  >
    <input
      :id="cbId"
      class="custom-control-input"
      type="checkbox"
      true-value="1"
      false-value="0"
      :disabled="disabled"
      v-model="model"
      :multiple="multiple"
      :name="name"
    />
    <label :for="cbId" class="custom-control-label">
      <slot>
        <span v-if="inline">&nbsp;</span>
      </slot>
    </label>
  </div>
</template>
<script>
import { randomString } from "./stringUtils";

export default {
  name: "base-checkbox",
  model: {
    prop: "checked",
  },
  props: {
    checked: {
      type: [Array, Boolean, String],
      description: "Whether checkbox is checked",
    },
    disabled: {
      type: Boolean,
      description: "Whether checkbox is disabled",
    },
    inline: {
      type: Boolean,
      description: "Whether checkbox is inline",
    },
    multiple: {
      type: Boolean,
      default: false,
      description: "Can select one or more items",
    },
    name: {
      type: String,
    },
  },
  data() {
    return {
      cbId: "",
      touched: false,
    };
  },
  computed: {
    model: {
      get() {
        return this.checked;
      },
      set(check) {
        if (!this.touched) {
          this.touched = true;
        }
        this.$emit("input", check);
      },
    },
  },
  mounted() {
    this.cbId = randomString();
  },
};
</script>
