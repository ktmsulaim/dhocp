<template>
  <div class="card-header border-0 bg-transparent">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="mb-0">Export Documents (ZIP)</h3>
      </div>
      <div class="col-auto text-right">
        <div class="btn-group" role="group">
          <base-button
            type="secondary"
            size="sm"
            @click="$inertia.get($route('export.students.index'))"
          >
            <i class="ni ni-bullet-list-67 text-info mr-1"></i>
            Export Data (Excel)
          </base-button>
          <base-button type="primary" size="sm" disabled>
            <i class="ni ni-archive-2 mr-1"></i>
            Export Documents (ZIP)
          </base-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "export-header",
};
</script>
