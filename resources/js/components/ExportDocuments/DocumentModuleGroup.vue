<template>
  <div class="border rounded mb-3">
    <div
      class="bg-secondary rounded-top px-3 py-2 d-flex justify-content-between align-items-center"
    >
      <span class="font-weight-bold text-sm">{{ module.name }}</span>
      <button
        v-if="itemIds.length > 0"
        type="button"
        class="btn btn-link btn-sm p-0"
        @click="toggleAll"
      >
        {{ isAllSelected ? "Clear" : "Select all" }}
      </button>
    </div>
    <div class="px-3 pt-3 pb-1">
      <div class="row">
        <div v-for="item in items" :key="item.id" class="col-md-6 mb-2">
          <base-checkbox
            :checked="selectedItems.includes(item.id) ? '1' : '0'"
            class="custom-control-alternative"
            @input="setItem(item.id, $event)"
          >
            {{ item.label }}
          </base-checkbox>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { isChecked } from "./checkboxUtils";

export default {
  name: "document-module-group",
  props: {
    module: {
      type: Object,
      required: true,
    },
    selectedItems: {
      type: Array,
      default: () => [],
    },
  },
  computed: {
    items() {
      return this.module.items || [];
    },
    itemIds() {
      return this.items.map((item) => item.id);
    },
    isAllSelected() {
      return (
        this.itemIds.length > 0 &&
        this.itemIds.every((id) => this.selectedItems.includes(id))
      );
    },
  },
  methods: {
    setItem(itemId, value) {
      const others = this.selectedItems.filter((id) => id !== itemId);
      this.$emit("update:selectedItems", isChecked(value) ? [...others, itemId] : others);
    },
    toggleAll() {
      const remaining = this.selectedItems.filter((id) => !this.itemIds.includes(id));
      this.$emit(
        "update:selectedItems",
        this.isAllSelected ? remaining : [...remaining, ...this.itemIds]
      );
    },
  },
};
</script>
