import QrScanner from "qr-scanner";

const videoElement  = document.getElementById("video");
const resultLine    = document.getElementById("result_data");

let last_result = null;
let qrcode      = null;

function startQrScanner() {
  if (qrcode) {
    qrcode.start()?.catch(console.error);
    return;
  }

  qrcode = new QrScanner(
    videoElement,
    (result) => {
      const data = result.data ?? result;

      // Skip duplicates within cooldown
      if (data === last_result) return;
      last_result = data;

      resultLine.value = data;
      resultLine.dispatchEvent(new Event("input", { bubbles: true }));
      console.log("✅ QR Scanned:", data);

      // Optional: ignore the same code for 1 second
      setTimeout(() => {
        last_result = null;
      }, 1000);
    },
    {
      returnDetailedScanResult: true,
      highlightCodeOutline: true,
      highlightScanRegion: true,
    }
  );

  qrcode
    .start()
    .then(() => console.log("✅ QR scanner started"))
    .catch((err) => console.error("❌ Scanner error:", err));
}

function stopQrScanner() {
  if (qrcode) {
    qrcode.stop();
    console.log("🛑 QR scanner stopped");
  }
}

// Optional helpers you can export
export { qrcode, startQrScanner, stopQrScanner };

// Start immediately (or call startQrScanner() from your UI event)
startQrScanner();