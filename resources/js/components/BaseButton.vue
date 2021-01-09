<template>
  <component
    :is="tag"
    :type="tag === 'button' ? nativeType : ''"
    @click="handleClick"
    class="btn"
    :class="classes"
  >
    <span
      class="btn-inner--icon"
      v-if="$slots.icon || (icon && $slots.default)"
    >
      <slot name="icon">
        <i :class="icon"></i>
      </slot>
    </span>
    <i v-if="!$slots.default" :class="icon"></i>
    <span
      class="btn-inner--text"
      v-if="$slots.icon || (icon && $slots.default)"
    >
      <slot>
        <span :class="addMarginWhenLoading">{{ text }}</span>
      </slot>
    </span>
    <svg
      v-if="loading"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      width="23px"
      height="23px"
      viewBox="0 0 100 100"
      preserveAspectRatio="xMidYMid"
    >
      <circle
        cx="50"
        cy="50"
        fill="none"
        stroke="#ffffff"
        stroke-width="7"
        r="35"
        stroke-dasharray="164.93361431346415 56.97787143782138"
      >
        <animateTransform
          attributeName="transform"
          type="rotate"
          repeatCount="indefinite"
          dur="1s"
          values="0 50 50;360 50 50"
          keyTimes="0;1"
        ></animateTransform>
      </circle>
    </svg>
    <slot v-if="!$slots.icon && !icon"></slot>
  </component>
</template>
<script>
export default {
  name: "base-button",
  props: {
    tag: {
      type: String,
      default: "button",
      description: "Button tag (default -> button)",
    },
    type: {
      type: String,
      default: "default",
      description: "Button type (e,g primary, danger etc)",
    },
    size: {
      type: String,
      default: "",
      description: "Button size lg|sm",
    },
    textColor: {
      type: String,
      default: "",
      description: "Button text color (e.g primary, danger etc)",
    },
    nativeType: {
      type: String,
      default: "button",
      description: "Button native type (e.g submit,button etc)",
    },
    icon: {
      type: String,
      default: "",
      description: "Button icon",
    },
    text: {
      type: String,
      default: "",
      description: "Button text in case not provided via default slot",
    },
    outline: {
      type: Boolean,
      default: false,
      description: "Whether button style is outline",
    },
    rounded: {
      type: Boolean,
      default: false,
      description: "Whether button style is rounded",
    },
    iconOnly: {
      type: Boolean,
      default: false,
      description: "Whether button contains only an icon",
    },
    block: {
      type: Boolean,
      default: false,
      description: "Whether button is of block type",
    },
    loading: {
      type: Boolean,
      default: false,
      description: "Add a circle to button",
    },
  },
  computed: {
    classes() {
      let btnClasses = [
        { "btn-block": this.block },
        { "rounded-circle": this.rounded },
        { "btn-icon-only": this.iconOnly },
        { [`text-${this.textColor}`]: this.textColor },
        { "btn-icon": this.icon || this.$slots.icon },
        this.type && !this.outline ? `btn-${this.type}` : "",
        this.outline ? `btn-outline-${this.type}` : "",
      ];
      if (this.size) {
        btnClasses.push(`btn-${this.size}`);
      }
      return btnClasses;
    },
    addMarginWhenLoading() {
      if (this.loading) {
        return "ml-2";
      } else {
        return "";
      }
    },
  },
  methods: {
    handleClick(evt) {
      this.$emit("click", evt);
    },
  },
};
</script>
<style>
</style>
