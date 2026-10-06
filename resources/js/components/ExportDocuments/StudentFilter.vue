<template>
  <div class="form-group mb-0">
    <select
      :value="value"
      class="form-control form-control-alternative"
      @change="onChange"
    >
      <option value="active">
        Active students ({{ stats.activeStudents }})
      </option>
      <option value="all">All students ({{ stats.totalStudents }})</option>
      <optgroup label="Batches">
        <option v-for="batch in batches" :key="batch.id" :value="batch.id">
          {{ batch.name }}
        </option>
      </optgroup>
    </select>
  </div>
</template>

<script>
export default {
  name: "student-filter",
  props: {
    value: {
      type: [String, Number],
      default: "active",
    },
    batches: {
      type: Array,
      default: () => [],
    },
    stats: {
      type: Object,
      default: () => ({ totalStudents: 0, activeStudents: 0 }),
    },
  },
  methods: {
    onChange(event) {
      const selected = event.target.value;
      const batch = this.batches.find((b) => String(b.id) === selected);
      this.$emit("input", batch ? batch.id : selected);
    },
  },
};
</script>
