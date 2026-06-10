import { query } from '../config/db.js';

async function run() {
  try {
    console.log("Describing menu_heading...");
    try {
      const descHeading = await query("DESCRIBE menu_heading");
      console.log("menu_heading columns:", descHeading);
    } catch (e) {
      console.log("Describe menu_heading failed:", e.message);
    }

    console.log("Describing menu...");
    try {
      const descMenu = await query("DESCRIBE menu");
      console.log("menu columns:", descMenu);
    } catch (e) {
      console.log("Describe menu failed:", e.message);
    }

    console.log("Describing admin_master...");
    try {
      const descAdmin = await query("DESCRIBE admin_master");
      console.log("admin_master columns:", descAdmin);
    } catch (e) {
      console.log("Describe admin_master failed:", e.message);
    }
  } catch (err) {
    console.error("General error:", err);
  }
}

run();
