import QrScanner from 'qr-scanner';

export default (initialState) => ({
    isOpen: false,
    isScanning: false,
    hasCamera: false,
    lastCode: {},
    scanner: null,

    init() {
        this.hasCamera = QrScanner.hasCamera();

        if (this.hasCamera) {
            this.scanner = new QrScanner(
                this.$refs.video,
                this.onDecode.bind(this),
                {
                    returnDetailedScanResult: true,
                    highlightScanRegion: true,
                    overlay: this.$refs.highlight,
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
        if (!result.data.startsWith('hydrofon:')) {
            console.log('Invalid QR Code')
            return;
        }

        let readCode = result.data.substring(9);

        if (!this.codeShouldBeProcessed(readCode)) {
            return;
        }

        this.lastCode = {
            id: readCode,
            readAt: new Date(),
        };

        console.log('Valid QR Code', readCode);
        this.$dispatch('qrcoderead', readCode);
    },

    codeShouldBeProcessed(code) {
        // Avoid multiple readings by rejecting same value for 30 seconds.
        if (this.lastCode.id === code && (new Date) - this.lastCode.readAt > 30000) {
            this.lastCode = {};
        }

        return code !== this.lastCode.id;
    },

    outsideClick() {
        if (!this.isOpen) {
            return;
        }

        this.isOpen = false;
    }
})
