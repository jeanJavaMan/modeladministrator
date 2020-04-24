<style>
    .tr-select:hover {
        background-color: #4d94ff !important;
        color: white;
        cursor: pointer;
    }

    .tr-expand {
        background-color: white !important;
        display: none;
    }

    .btn {
        min-width: 100px !important;
        color: white;
    }

    .resizer {
        position: absolute;
        top: 0;
        right: -8px;
        bottom: 0;
        left: auto;
        width: 16px;
        cursor: col-resize;
    }

    .resizer:hover {
        background-color: #33cc33;
        opacity: 0.4;
    }

    .tr-head {
        background-color: #535353;
        color: white;
    }

    .focused-div {
        z-index: 1039;
        position: relative;
    }
    .background-show{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1038;
        width: 100vw;
        height: 100vh;
        background-color: #000;
        opacity: .5;
        transition: opacity .15s linear;
    }
    .colapse-junto{
        margin-top: -18px !important;
    }
    .card-primary:not(.card-outline) .card-header {
        background-color: #007bff;
    }
    .card-secondary:not(.card-outline) .card-header {
        background-color: #6c757d;
    }
</style>
