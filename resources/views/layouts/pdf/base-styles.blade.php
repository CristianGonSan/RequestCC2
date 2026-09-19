<style>
    /* ============================================
        RESET BÁSICO
    ============================================ */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Helvetica", "DejaVu Sans", sans-serif;
        font-size: 11px;
        color: #2c3338;
        line-height: 1.5;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    /* ============================================
        TIPOGRAFÍA
    ============================================ */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Helvetica", "DejaVu Sans", sans-serif;
        font-weight: 700;
        color: #1a2332;
        margin-top: 0;
        margin-bottom: 8px;
    }

    h1 {
        font-size: 22px;
    }

    h2 {
        font-size: 18px;
    }

    h3 {
        font-size: 15px;
    }

    h4 {
        font-size: 13px;
    }

    h5 {
        font-size: 12px;
    }

    h6 {
        font-size: 11px;
    }

    p {
        margin: 0 0 8px 0;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-white {
        color: #ffffff !important;
    }

    .text-dark {
        color: #1a2332 !important;
    }

    .text-primary {
        color: #2d5f8a !important;
    }

    .text-success {
        color: #2f7d4f !important;
    }

    .text-danger {
        color: #a3333d !important;
    }

    .text-warning {
        color: #a8721c !important;
    }

    .text-info {
        color: #2d7d8a !important;
    }

    .text-left {
        text-align: left !important;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .text-capitalize {
        text-transform: capitalize;
    }

    .font-weight-bold {
        font-weight: 700;
    }

    .font-weight-normal {
        font-weight: 400;
    }

    .font-italic {
        font-style: italic;
    }

    .small {
        font-size: 9px;
    }

    .lead {
        font-size: 13px;
    }

    /* ============================================
        COLORES DE FONDO
    ============================================ */
    .bg-primary {
        background-color: #2d5f8a;
        color: #ffffff;
    }

    .bg-secondary {
        background-color: #6c757d;
        color: #ffffff;
    }

    .bg-success {
        background-color: #2f7d4f;
        color: #ffffff;
    }

    .bg-danger {
        background-color: #a3333d;
        color: #ffffff;
    }

    .bg-warning {
        background-color: #f4e3c1;
        color: #5c4413;
    }

    .bg-info {
        background-color: #d9edf0;
        color: #205a63;
    }

    .bg-light {
        background-color: #f5f6f7;
    }

    .bg-dark {
        background-color: #1a2332;
        color: #ffffff;
    }

    .bg-white {
        background-color: #ffffff;
    }

    /* ============================================
        ESPACIADOS - MARGIN (m-0 a m-5)
    ============================================ */
    .m-0 {
        margin: 0;
    }

    .m-1 {
        margin: 4px;
    }

    .m-2 {
        margin: 8px;
    }

    .m-3 {
        margin: 12px;
    }

    .m-4 {
        margin: 16px;
    }

    .m-5 {
        margin: 24px;
    }

    .mt-0 {
        margin-top: 0;
    }

    .mt-1 {
        margin-top: 4px;
    }

    .mt-2 {
        margin-top: 8px;
    }

    .mt-3 {
        margin-top: 12px;
    }

    .mt-4 {
        margin-top: 16px;
    }

    .mt-5 {
        margin-top: 24px;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-1 {
        margin-bottom: 4px;
    }

    .mb-2 {
        margin-bottom: 8px;
    }

    .mb-3 {
        margin-bottom: 12px;
    }

    .mb-4 {
        margin-bottom: 16px;
    }

    .mb-5 {
        margin-bottom: 24px;
    }

    .ml-0 {
        margin-left: 0;
    }

    .ml-1 {
        margin-left: 4px;
    }

    .ml-2 {
        margin-left: 8px;
    }

    .ml-3 {
        margin-left: 12px;
    }

    .ml-4 {
        margin-left: 16px;
    }

    .ml-5 {
        margin-left: 24px;
    }

    .mr-0 {
        margin-right: 0;
    }

    .mr-1 {
        margin-right: 4px;
    }

    .mr-2 {
        margin-right: 8px;
    }

    .mr-3 {
        margin-right: 12px;
    }

    .mr-4 {
        margin-right: 16px;
    }

    .mr-5 {
        margin-right: 24px;
    }

    .my-0 {
        margin-top: 0;
        margin-bottom: 0;
    }

    .my-1 {
        margin-top: 4px;
        margin-bottom: 4px;
    }

    .my-2 {
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .my-3 {
        margin-top: 12px;
        margin-bottom: 12px;
    }

    .my-4 {
        margin-top: 16px;
        margin-bottom: 16px;
    }

    .my-5 {
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .mx-0 {
        margin-left: 0;
        margin-right: 0;
    }

    .mx-1 {
        margin-left: 4px;
        margin-right: 4px;
    }

    .mx-2 {
        margin-left: 8px;
        margin-right: 8px;
    }

    .mx-3 {
        margin-left: 12px;
        margin-right: 12px;
    }

    .mx-4 {
        margin-left: 16px;
        margin-right: 16px;
    }

    .mx-5 {
        margin-left: 24px;
        margin-right: 24px;
    }

    /* ============================================
        ESPACIADOS - PADDING (p-0 a p-5)
    ============================================ */
    .p-0 {
        padding: 0;
    }

    .p-1 {
        padding: 4px;
    }

    .p-2 {
        padding: 8px;
    }

    .p-3 {
        padding: 12px;
    }

    .p-4 {
        padding: 16px;
    }

    .p-5 {
        padding: 24px;
    }

    .pt-0 {
        padding-top: 0;
    }

    .pt-1 {
        padding-top: 4px;
    }

    .pt-2 {
        padding-top: 8px;
    }

    .pt-3 {
        padding-top: 12px;
    }

    .pt-4 {
        padding-top: 16px;
    }

    .pt-5 {
        padding-top: 24px;
    }

    .pb-0 {
        padding-bottom: 0;
    }

    .pb-1 {
        padding-bottom: 4px;
    }

    .pb-2 {
        padding-bottom: 8px;
    }

    .pb-3 {
        padding-bottom: 12px;
    }

    .pb-4 {
        padding-bottom: 16px;
    }

    .pb-5 {
        padding-bottom: 24px;
    }

    .pl-0 {
        padding-left: 0;
    }

    .pl-1 {
        padding-left: 4px;
    }

    .pl-2 {
        padding-left: 8px;
    }

    .pl-3 {
        padding-left: 12px;
    }

    .pl-4 {
        padding-left: 16px;
    }

    .pl-5 {
        padding-left: 24px;
    }

    .pr-0 {
        padding-right: 0;
    }

    .pr-1 {
        padding-right: 4px;
    }

    .pr-2 {
        padding-right: 8px;
    }

    .pr-3 {
        padding-right: 12px;
    }

    .pr-4 {
        padding-right: 16px;
    }

    .pr-5 {
        padding-right: 24px;
    }

    .py-0 {
        padding-top: 0;
        padding-bottom: 0;
    }

    .py-1 {
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .py-2 {
        padding-top: 8px;
        padding-bottom: 8px;
    }

    .py-3 {
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .py-4 {
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .py-5 {
        padding-top: 24px;
        padding-bottom: 24px;
    }

    .px-0 {
        padding-left: 0;
        padding-right: 0;
    }

    .px-1 {
        padding-left: 4px;
        padding-right: 4px;
    }

    .px-2 {
        padding-left: 8px;
        padding-right: 8px;
    }

    .px-3 {
        padding-left: 12px;
        padding-right: 12px;
    }

    .px-4 {
        padding-left: 16px;
        padding-right: 16px;
    }

    .px-5 {
        padding-left: 24px;
        padding-right: 24px;
    }

    /* ============================================
        BORDES
    ============================================ */
    .border {
        border: 1px solid #dcdfe3;
    }

    .border-top {
        border-top: 1px solid #dcdfe3;
    }

    .border-bottom {
        border-bottom: 1px solid #dcdfe3;
    }

    .border-left {
        border-left: 1px solid #dcdfe3;
    }

    .border-right {
        border-right: 1px solid #dcdfe3;
    }

    .border-0 {
        border: none;
    }

    .border-primary {
        border-color: #2d5f8a;
    }

    .border-success {
        border-color: #2f7d4f;
    }

    .border-danger {
        border-color: #a3333d;
    }

    .rounded {
        border-radius: 3px;
    }

    .rounded-0 {
        border-radius: 0;
    }

    /* ============================================
        TABLAS (equivalente a .table de Bootstrap)
    ============================================ */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }

    .table th {
        background-color: #eef1f4;
        color: #1a2332;
        font-weight: 700;
        text-align: left;
        padding: 6px 8px;
        border-bottom: 2px solid #cfd4da;
        font-size: 10px;
        text-transform: uppercase;
    }

    .table td {
        padding: 6px 8px;
        border-bottom: 1px solid #e3e6e9;
        vertical-align: top;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dcdfe3;
    }

    .table-striped tr:nth-child(even) td {
        background-color: #f7f8f9;
    }

    .table-sm th,
    .table-sm td {
        padding: 3px 6px;
    }

    /* ============================================
        LAYOUT (DomPDF no soporta flex/grid -> usar float o display:inline-block)
    ============================================ */
    .row::after {
        content: "";
        display: table;
        clear: both;
    }

    .col {
        float: left;
        padding: 0 6px;
    }

    .col-2 {
        width: 16.66%;
    }

    .col-3 {
        width: 25%;
    }

    .col-4 {
        width: 33.33%;
    }

    .col-6 {
        width: 50%;
    }

    .col-8 {
        width: 66.66%;
    }

    .col-9 {
        width: 75%;
    }

    .col-12 {
        width: 100%;
    }

    .d-block {
        display: block;
    }

    .d-inline {
        display: inline;
    }

    .d-inline-block {
        display: inline-block;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }

    .w-25 {
        width: 25%;
    }

    .w-50 {
        width: 50%;
    }

    .w-75 {
        width: 75%;
    }

    .w-100 {
        width: 100%;
    }

    /* ============================================
        BADGES / ETIQUETAS DE ESTADO
    ============================================ */
    .badge {
        display: inline-block;
        padding: 2px 8px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        color: #ffffff;
    }

    .badge-primary {
        background-color: #2d5f8a;
    }

    .badge-success {
        background-color: #2f7d4f;
    }

    .badge-danger {
        background-color: #a3333d;
    }

    .badge-warning {
        background-color: #a8721c;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    /* ============================================
        ELEMENTOS DE REPORTE (propios, no-Bootstrap)
    ============================================ */
    .page-header {
        border-bottom: 2px solid #1a2332;
        padding-bottom: 10px;
        margin-bottom: 16px;
    }

    .page-footer {
        border-top: 1px solid #dcdfe3;
        padding-top: 6px;
        font-size: 9px;
        color: #6c757d;
    }

    .section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2d5f8a;
        border-bottom: 1px solid #dcdfe3;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }

    .divider {
        border: none;
        border-top: 1px solid #dcdfe3;
        margin: 12px 0;
    }

    .page-break {
        page-break-after: always;
    }

    .table-layout {
        width: 100%;
        border-collapse: collapse;
    }

    .table-layout td {
        border: none;
        padding: 0;
        vertical-align: top;
    }
</style>
