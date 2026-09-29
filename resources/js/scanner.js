import QrScanner from "qr-scanner";

const videoElement = document.getElementById("video");
const resultLine   = document.getElementById("result_data");

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

      if (data === last_result) return;
      last_result = data;

      // Update the visible input
      resultLine.value = data;
      resultLine.dispatchEvent(new Event("input", { bubbles: true }));
      console.log("✅ QR Scanned:", data);

      // ── Call success_entry() so the servo + suspension check actually runs ──
      if (window.Livewire) {
        Livewire.dispatch('success_entry', { value: data });
      }

      setTimeout(() => { last_result = null; }, 1000);
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

export { qrcode, startQrScanner, stopQrScanner };

startQrScanner();