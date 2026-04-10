# Real-Time Monitoring Dashboard for Government Data (DDA)

## Project Overview
This web-based dashboard was developed during my internship at the **Information Systems and Dissemination (SID) Division of the Central Statistics Agency (BPS) Central Java**. It was built to solve the inefficiency of manual, row-by-row tracking of sectoral data submissions ("Daerah Dalam Angka" / DDA) from various regional departments (OPD).

*Disclaimer: The source code shared here has been sanitized. All credentials, sensitive government configurations, and real databases have been removed or replaced with dummy data for portfolio purposes.*

## Tech Stack
* **Backend:** PHP (CodeIgniter 3 MVC Framework)
* **Database:** MySQL (Advanced Aggregation)
* **Frontend:** HTML, CSS, JavaScript
* **Data Visualization:** Highcharts JS
* **Environment:** Localhost (XAMPP), Production Server (PHP 8)

## File Description
* **Models:** m_kelolakegiatan.php
* **Views:** d_amain.php
* **Controllers:** admin.php

## Key Features & Technical Highlights
1. **Server-Side SQL Aggregation:** Instead of loading raw data into PHP arrays which consumes high memory, I implemented conditional SQL aggregation (`SUM(CASE WHEN...)`) directly in the database. This calculates data statuses (Done, Pending, Empty) in a single query, ensuring high rendering speed.
2. **Interactive Data Visualization:** 
   * **Donut Charts:** For a quick global overview of the province's data completion percentage.
   * <img width="1473" height="544" alt="Screenshot 2025-12-15 152159" src="https://github.com/user-attachments/assets/7db7d237-de8a-4482-a4ac-480a894dc7b4" />
   * **100% Stacked Bar & Column Charts:** For granular, fair-ratio comparisons of workload and performance across different divisions and regional departments (OPD).
   * <img width="1474" height="600" alt="Screenshot 2025-12-15 152452" src="https://github.com/user-attachments/assets/70ed90f3-aa05-45e9-b52f-ba23a6c42750" />
   * <img width="1472" height="770" alt="Screenshot 2025-12-15 152803" src="https://github.com/user-attachments/assets/b64d50f6-3c93-45c4-a1e5-71ff11490fd7" />
3. **Sticky Session Filters:** Developed dynamic multi-filtering based on departments/teams that persists during data editing, improving the admin's workflow efficiency.
4. **Automated Export:** Integrated a feature to export the live tracking reports directly to Excel (.xls).

## Impact
The implementation of this dashboard transformed the management's evaluation process. By providing visual, color-coded indicators, the leadership team could instantly identify which departments were lagging behind, accelerating the data validation and follow-up process significantly.
