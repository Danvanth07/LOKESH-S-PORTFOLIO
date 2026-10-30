# Lokesh Pon – Civil Engineering & BIM Portfolio

A full-stack portfolio web application for **Lokesh Pon** (Civil Engineering Student at Government College of Engineering - IRTT, Erode), built using **PHP** and **Vue.js 3**.

---

## 🏗️ All 10 Mandatory Sections Included

1. **Home / Hero Section (Mandatory)**
   - **Full Name**: Lokesh Pon
   - **Role**: Civil Engineering Student
   - **College**: Government College of Engineering (IRTT), Erode
   - **Location**: Ramanathapuram-623501,TamilNadu
   - **Professional Photograph**: Profile avatar with live update/upload capability
   - **Cover Photo**: High-resolution 3D architectural night render with live **"Change Cover Photo"** upload tool
   - **Tagline**: *"Civil Engineering student skilled in AutoCAD, SketchUp, Revit, and STAAD.Pro, experienced in 2D drafting, 3D building modeling, and practical design solutions."*
   - **Actions**: Direct buttons for **Download Resume (PDF)**, **View Full Portfolio (PDF)**, and **Contact Me**
   - **Quick Stat Metrics**: Final Year (IRTT Erode), 60+ Hrs Autodesk Certified, 4+ Projects

2. **About Me**
   - Personal introduction & civil engineering philosophy
   - Academic year: Final Year (Expected Graduation: 2027)
   - College & University: Government College of Engineering (IRTT), Erode (Affiliated with Anna University)
   - 4 Areas of Interest: BIM & Architectural Modeling, 3D Visualization, Structural Analysis, Geotechnical & Foundation Works
   - Career objectives & 5 key personal strengths (precision drafting, visual realism, site adaptability, yoga-forged discipline, continuous learning)

3. **Education**
   - **B.E. Civil Engineering**: Government College of Engineering (IRTT), Erode | CGPA: **6.92** | Expected: **2027**
   - **HSC (Class XII)**: Infant Jesus Matric Hr. Sec. School, Ramanathapuram | **70.33%** | Year: **2023**

4. **Technical Skills (Only Actual Skills Included)**
   - **Software**: AutoCAD (Certified 60 Hrs), Autodesk Revit, SketchUp Pro, Enscape 3D, STAAD.Pro (Basic/Intermediate), Tekla Structures (Under Active Learning), MS Excel
   - **Field & Practical**: Quantity Estimation & Costing (BOQ), Land Surveying & Levelling (Auto Level/Dumpy Level), Structural Analysis (IS 456 / IS 875), Foundation Engineering (Pile layout, boring, reinforcement cage, tremie concrete), Bar Bending Schedule (BBS), Municipal Bylaws

5. **Projects (Very Important)**
   Includes interactive Engineering Case Study drawer/modal with:
   - **Project Title & Category**
   - **Problem Statement**
   - **Project Objectives**
   - **Methodology & Technical Workflow**
   - **Software & Tools Utilized**
   - **Lokesh's Direct Contribution**
   - **Project Photographs & Drawings Gallery** (with full-screen lightbox for 3D renders)
   - **Final Outcome**
   *Projects featured:*
   - *Sketchup + Enscape (Complete 3D Architectural Visualization & Walkthrough Gallery - 11 Renders)*
   - *Residential Building Drafting and Planning (AutoCAD 2021 - Official Autodesk Credential)*
   - *Revit Architectural Design & Parametric BIM Modeling (14 Comprehensive BIM Project Case Study Photographs)*
   - *Structural Modeling & Frame Analysis (STAAD.Pro per IS 456 & IS 875)*

6. **Internship & Industrial Training**
   - **Builtec / Builtech – Chennai**: Bored pile foundation works, pile layout setting out, borehole drilling with bentonite slurry, circular reinforcement cages, tremie pipe concreting, slump testing, cube casting, and QA/QC documentation.
   - **MR Construction – Ramanathapuram**: Residential structural framing, formwork alignment, rebar placement verification, aggregate/sand silt testing, and concrete curing supervision.

7. **Certifications & Courses**
   - **Autodesk Authorized Training Center / CADCENTER COCHIN**:
     - *Certificate of Completion: AutoCAD Civil Drafting and Construction Drawings*
     - Certificate No: `AP702911098608865740601` | Instructor: `SANOJ P D` | Date: `05-JANUARY-2026` | `60 Hours`
     - Interactive high-resolution certificate modal viewer
   - *Revit Architecture & BIM Fundamentals*
   - *STAAD.Pro Structural Modeling & Analysis*
   - *Tekla Structures - Steel & Concrete Detailing (Currently Under Active Learning)*

8. **Practical & Field Experience**
   - Interactive cards detailing 6 practical field operations:
     1. Land Surveying (Total Station, Theodolite, Chain Surveying, Contouring)
     2. Levelling (Auto Level, Dumpy Level, Fly Levelling, Rise & Fall / Collimation methods)
     3. Soil Testing (Core cutter field dry density, sieve analysis, liquid limit, plastic limit)
     4. Concrete Testing (Slump cone test, compressive strength of $150\text{ mm}$ cubes at 7 & 28 days)
     5. Pile Foundation Supervision (Rotary boring, bentonite slurry viscosity, tremie concreting)
     6. Quantity Estimation & Costing (Centerline method, BOQ preparation in Excel)

9. **Achievements & Activities**
   - **Academic / Technical**: Paper Presentation at **Government College of Technology (GCT) - Coimbatore** on *"BIM IN CONSTRUCTION – Revolutionizing Project Lifecycle, Clash Detection and Cost Efficiency"*; active symposium participant.
   - **Sports & Extra-Curricular**:
     - **1st Prize Winner in Multiple State and District Level YOGA Competitions** in Tamil Nadu.
     - **2x Participant in the International YOGA Championship**.
     - Highlighting how physical poise, breath control, and focus translate to meticulous drafting and site rigor.

10. **Contact Section (Mandatory)**
    - **Email**: `lokeshpon2005@gmail.com`
    - **Mobile**: `+91 9345029806` (Click to call / WhatsApp)
    - **LinkedIn**: `https://www.linkedin.com/in/lokeshpon15112005`
    - **Location**: `Ramanathapuram-623501,TamilNadu`
    - **Download Documents**: One-click download for `Lokesh pon resume.pdf` and `Portfolio.pdf`
    - **Interactive Contact Form**: Direct submission processed by `api/contact.php`, stored in `data/inquiries.json`, with real-time feedback.

---

## ⚡ Tech Stack & Architecture

- **Backend**: **PHP 8.2**
  - `index.php`: Server-rendered HTML template with pre-hydrated JSON for zero-lag initial load.
  - `api/data.php`: REST endpoint returning portfolio JSON structure.
  - `api/contact.php`: Validates, sanitizes, and stores inquiries into `data/inquiries.json`.
  - `api/upload.php`: Handles live cover photo and profile photo uploads (JPG, PNG, WEBP up to 15MB) with automatic data binding.
  - `api/download.php`: Secure PDF download stream for Resume and Portfolio.
- **Frontend**: **Vue.js 3**
  - Standalone production build stored locally in `assets/js/vue.global.prod.js` with CDN fallback.
  - Reactive filterable projects & skills.
  - Project Engineering Case Study modal with photo lightbox.
  - Live Cover Photo & Avatar uploader.
  - Light Mode & Dark Blueprint Mode toggle.

---

## 🚀 How to Run the Application

### Option 1: Double-Click Launcher (Easiest)
Simply double-click:
```bat
run_portfolio.bat
```
This automatically boots the PHP server and opens `http://localhost:8080` in your browser.

### Option 2: Command Line
Open PowerShell or Command Prompt in `E:\LOKI PORFOLIO`:
```powershell
C:\xampp\php\php.exe -S localhost:8080
```
Then visit `http://localhost:8080` in your web browser.

---

## 📂 Project Structure

```
E:\LOKI PORFOLIO\
├── index.php                 # Main entry template with server-hydrated Vue 3 app
├── run_portfolio.bat         # 1-click server start script
├── README.md                 # Detailed documentation
├── Lokesh pon resume.pdf     # Original resume document
├── Portfolio.pdf             # Original portfolio document with renders & certificates
├── api\
│   ├── contact.php           # Processes contact inquiries, validates & stores to JSON
│   ├── data.php              # Returns portfolio data in JSON format
│   ├── download.php          # Streams resume/portfolio PDF downloads
│   └── upload.php            # Handles cover photo and profile photo upload
├── assets\
│   ├── css\
│   │   └── style.css         # Civil engineering & architectural blueprint styling
│   ├── js\
│   │   ├── app.js            # Vue 3 reactive app logic & state
│   │   └── vue.global.prod.js# Standalone Vue 3 production script
│   ├── images\               # High-resolution renders and certificates
│   │   ├── hero-cover.jpg    # Default 3D exterior night render of villa
│   │   ├── lokesh-photo.jpg  # Profile photo
│   │   ├── cert-autocad.jpg  # Autodesk CADCenter Cochin Certificate
│   │   ├── sketchup-villa-night.jpg
│   │   ├── sketchup-villa-day.jpg
│   │   ├── sketchup-living-stairs.jpg
│   │   ├── sketchup-dining.jpg
│   │   ├── sketchup-master-bed.jpg
│   │   ├── sketchup-bedroom2.jpg
│   │   ├── sketchup-bunk-bedroom.jpg
│   │   ├── sketchup-sage-bedroom.jpg
│   │   ├── sketchup-sage-bedroom2.jpg
│   │   └── sketchup-balcony.jpg
│   └── uploads\              # Storage for user-uploaded cover photos & avatars
└── data\
    ├── portfolio_data.json   # Structured data for all 10 portfolio sections
    └── inquiries.json        # Contact form messages storage
```

