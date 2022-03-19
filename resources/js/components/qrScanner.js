import QrScanner from 'qr-scanner';

export default (initialState) => ({
    isOpen: false,
    isScanning: false,
    hasCamera: false,
    scanner: null,

    init() {
        this.hasCamera = QrScanner.hasCamera();

        if (this.hasCamera) {
            this.scanner = new QrScanner(
                this.$refs.video,
                this.onDecode.bind(this),
                {
                    highlightScanRegion: true,
                    returnDetailedScanResult: true,
                }
            );
        }
    },

    start() {
        this.scanner.start();
        this.isScanning = true;
    },

    stop() {
        this.scanner.stop();
        this.isScanning = false;
        this.isOpen = false;
    },

    onDecode(result) {
        if (result.data.startsWith('hydrofon:')) {
            console.log('Valid QR Code', result.data.substring(9))
            this.$dispatch('qrcoderead', result.data.substring(9))
        } else {
            console.log('Invalid QR Code')
        }
    },

    outsideClick() {
        if (!this.isOpen) {
            return;
        }

        this.isOpen = false;
    }
})
