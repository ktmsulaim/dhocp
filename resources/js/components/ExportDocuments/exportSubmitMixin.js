import { buildExportPayload, runDocumentsExport } from "./exportFlow";

const DOUBLE_SUBMIT_GUARD_MS = 3000;
const NO_SELECTION_ERROR = "Please select at least one document field or enable profile photos.";

export default {
  data() {
    return {
      loading: false,
      error: null,
      notice: null,
      summary: null,
    };
  },
  computed: {
    exportPayload() {
      return buildExportPayload(this);
    },
  },
  watch: {
    exportPayload() {
      this.notice = null;
    },
  },
  created() {
    const errors = this.$page.props.errors;
    if (errors && errors.export) this.error = [].concat(errors.export)[0];
  },
  beforeDestroy() {
    clearTimeout(this.releaseTimer);
  },
  methods: {
    submitExport() {
      if (this.loading) return;
      this.error = null;
      this.notice = null;
      if (!this.hasSelection) {
        this.error = NO_SELECTION_ERROR;
        return;
      }
      this.loading = true;
      runDocumentsExport({
        summaryUrl: this.$route("export.documents.summary"),
        downloadUrl: this.$route("export.documents"),
        payload: this.exportPayload,
      })
        .then(({ summary, notice }) => {
          this.summary = summary;
          this.notice = notice;
          this.releaseTimer = setTimeout(() => {
            this.loading = false;
          }, DOUBLE_SUBMIT_GUARD_MS);
        })
        .catch((err) => {
          this.error = err.message;
          this.loading = false;
        });
    },
  },
};
