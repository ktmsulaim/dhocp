<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <export-header />
            <div class="card-body">
              <div class="row">
                <div class="col-lg-7">
                  <export-section :step="1" title="Students" subtitle="Choose who to export">
                    <student-filter v-model="students" :batches="batches" :stats="stats" />
                  </export-section>
                  <document-source-list
                    :step="2"
                    :modules="modules"
                    :include-photo.sync="includePhoto"
                    :selected-items.sync="selectedItems"
                    :total-photos="stats.totalPhotos"
                  />
                  <export-format-sections
                    :group-by.sync="groupBy"
                    :naming-format.sync="namingFormat"
                    :separator.sync="separator"
                  />
                </div>
                <div class="col-lg-5">
                  <div class="export-sidebar">
                    <archive-preview
                      :group-by="groupBy"
                      :naming-format="namingFormat"
                      :separator="separator"
                      :include-photo="includePhoto"
                    />
                    <export-summary-panel
                      v-bind="summaryLabels"
                      :loading="loading"
                      :disabled="!hasSelection"
                      :notice="notice"
                      :error="error"
                      @submit="submitExport"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/DashboardLayout";
import ExportHeader from "../../../components/ExportDocuments/ExportHeader";
import ExportSection from "../../../components/ExportDocuments/ExportSection";
import StudentFilter from "../../../components/ExportDocuments/StudentFilter";
import DocumentSourceList from "../../../components/ExportDocuments/DocumentSourceList";
import ExportFormatSections from "../../../components/ExportDocuments/ExportFormatSections";
import ArchivePreview from "../../../components/ExportDocuments/ArchivePreview";
import ExportSummaryPanel from "../../../components/ExportDocuments/ExportSummaryPanel";
import { buildSummaryLabels } from "../../../components/ExportDocuments/summaryLabels";
import { collectItemIds } from "../../../components/ExportDocuments/sourceItems";
import exportSubmitMixin from "../../../components/ExportDocuments/exportSubmitMixin";

export default {
  layout: DashboardLayout,
  mixins: [exportSubmitMixin],
  components: {
    ExportHeader,
    ExportSection,
    StudentFilter,
    DocumentSourceList,
    ExportFormatSections,
    ArchivePreview,
    ExportSummaryPanel,
  },
  props: {
    batches: { type: Array, default: () => [] },
    modules: { type: Array, default: () => [] },
    stats: {
      type: Object,
      default: () => ({ totalStudents: 0, activeStudents: 0, totalPhotos: 0 }),
    },
  },
  data() {
    return {
      students: "active",
      includePhoto: true,
      selectedItems: [],
      groupBy: "student_module",
      namingFormat: "enroll_document",
      separator: "underscore",
    };
  },
  computed: {
    hasSelection() {
      return this.includePhoto || this.selectedItems.length > 0;
    },
    summaryLabels() {
      return buildSummaryLabels(this);
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "Bulk Document & Photo Export");
    this.selectedItems = collectItemIds(this.modules);
  },
};
</script>

<style scoped>
@media (min-width: 992px) {
  .export-sidebar {
    position: sticky;
    top: 1.5rem;
  }
}
</style>
