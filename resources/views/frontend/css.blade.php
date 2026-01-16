<style>

    #list-table th {
        
    }
    .card-note {
        background: pink;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 400;
        margin-top: 5px;
        border-radius: 3px;
        margin-bottom: 16px;
    }

    #table-laporan-kandang th,
    #table-laporan-kandang td {
        padding: 6px 4px !important;
        font-size: 11px;
    }

    .me-0 {
        margin-right: 2px !important;
    }

    .badge-insoft {
        position: relative;
        top: 70px;
    }

    .btn-insoft {
        font-size: 10px;
        padding: 5px 8px;
        border-radius: 17px;
    }

    .btn-jam {
        margin-top: 33px;
    }

    .border-1 {
        border: 1px solid grey;
    }

    .pull-right {
        float: right;
    }

    .logo-lg img {
        width: 164px;
        height: 67px;
        margin-top: 10px;
        border-radius: 10px;
    }

    .simplebar {
        margin-top: 50px;
    }

    .btn-sm {
        font-size: 12px !important;
    }

    .wa-container {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Bubble chat */
    .wa-bubble {
        background: #ffffff;
        color: #333;
        padding: 10px 14px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        font-size: 13px;
        line-height: 1.3;
        animation: bubbleIn 0.6s ease forwards;
    }

    .wa-bubble strong {
        display: block;
        font-weight: 700;
        color: #25D366;
    }

    /* WhatsApp Button */
    .wa-float {
        width: 58px;
        height: 58px;
        background: #25D366;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        position: relative;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .wa-float:hover {
        transform: scale(1.12);
        background: #1ebe5d;
    }

    /* Online status dot */
    .wa-status {
        position: absolute;
        bottom: 6px;
        right: 6px;
        width: 12px;
        height: 12px;
        background: #00ff6a;
        border: 2px solid #fff;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }

    /* Animations */
    @keyframes bubbleIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 255, 106, 0.6);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(0, 255, 106, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 255, 106, 0);
        }
    }

    .btn-color {
        padding: 16px 36px;
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        background-size: 400% 400%;
        animation: colorShift 3s infinite;
    }

    @keyframes colorShift {
        0% {
            background: #ff3d00;
        }

        25% {
            background: #ff9100;
        }

        50% {
            background: #00c853;
        }

        75% {
            background: #2962ff;
        }

        100% {
            background: #ff3d00;
        }
    }
</style>

@if ($view == 'dashboard')
    <style>
        .widget-card {
            height: auto !important;
                background: linear-gradient(135deg, #d9fcb6, #8a9dc2);
            
            border: 2px solid orange;
            border-radius: 10px;
        }

        .widget-card2 {
            height: auto !important;
            background: linear-gradient(135deg, #d9fcb6, #e1ebff);
            
            border: 2px solid #cac1b1;
            border-radius: 10px;
        }

        .avatar-bulat {
            border-radius: 25px !important;
            border: 4px solid;
        }

        .widget-number {
            font-size:20px !important;
        }
    </style>
@endif
