/**
 * Mock data for Resource List View
 * Based on real Device Hub CSV export (tbl_devices.csv)
 * 
 * Contains 50 equipment entries from IPF Dresden
 * TODO: Replace with real database queries when DB access available
 */

const mockResources = [
    {
        id: 1,
        name: "(Abaqus) Abaqus FEA Software",
        location: "",
        department: "M1 (Materials Engineering)",
        method: "Simulation Software/FEM"
    },
    {
        id: 2,
        name: "(Agilent + Wyatt Technology) Modular SEC system (ambient) in aqueous eluent",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 3,
        name: "(Agilent + Wyatt Technology) modular SEC system (ambient) in Dimethyacetamide",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 4,
        name: "(Agilent + Wyatt Technology) Modular SEC system (ambient) in Pentafluorophenol",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 5,
        name: "(Agilent + Wyatt Technology) modular SEC system (ambient) in THF",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 6,
        name: "(Agilent) Bioanalyzer 2100",
        location: "B112",
        department: "B (all divisions)",
        method: "Automated Electrophoresis System"
    },
    {
        id: 7,
        name: "(Agilent) Cary 50 Spektrometer",
        location: "P04",
        department: "C2 (Bioactive and Responsive Polymers)",
        method: "Spectroscopy/UV-VIS"
    },
    {
        id: 8,
        name: "(Agilent) Cary 5000 UV-Vis-NIR",
        location: "W218",
        department: "P1 (Functional Colloidal Materials)",
        method: "Spectroscopy/UV-VIS"
    },
    {
        id: 9,
        name: "(Agilent) Cary 6000i",
        location: "P04",
        department: "C2 (Bioactive and Responsive Polymers)",
        method: "Spectroscopy/UV-VIS"
    },
    {
        id: 10,
        name: "(Agilent) Head-space GC-MS",
        location: "H223",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 11,
        name: "(Agilent) Modular SEC system (ambient) in Chloroform",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography/Size Exclusion Chromatography"
    },
    {
        id: 12,
        name: "(Agilent) Modular SEC system (ambient) in DMAc+H20",
        location: "H224",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Chromatography"
    },
    {
        id: 13,
        name: "(Agteks) DirecTwist 2B",
        location: "H5",
        department: "M2 (Processing Technology)",
        method: "Twining"
    },
    {
        id: 14,
        name: "(Analytik Jena US) UVP-Crosslinker",
        location: "",
        department: "M2 (Processing Technology)",
        method: "UV Treatment/UV-Chamber"
    },
    {
        id: 15,
        name: "(Analytik Jena) Specord 40",
        location: "P011",
        department: "P2 (Nanostructured Materials)",
        method: "Spectroscopy/UV-VIS"
    },
    {
        id: 16,
        name: "(Analytik Jena) Specord 210 PLUS BU und Zubehör",
        location: "P04",
        department: "C2 (Bioactive and Responsive Polymers)",
        method: "Spectroscopy/UV-VIS"
    },
    {
        id: 17,
        name: "(Anasys-Bruker) NanoIR 2 Nano Surfaces",
        location: "H213",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Spectroscopy/FT-IR"
    },
    {
        id: 18,
        name: "(Andor) Dragonfly spining disc confocal",
        location: "B313",
        department: "B (all divisions)",
        method: "Light Microscopy/Confocal"
    },
    {
        id: 19,
        name: "(anisoprint) Anisoprint Composer A4 Carbon 3D-Drucker",
        location: "M7",
        department: "M1 (Materials Engineering)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 20,
        name: "(Ankele) Single-screw extruder AE 1-35-25-7",
        location: "T1",
        department: "M2 (Processing Technology)",
        method: "Extrusion/Single-Screw Extrusion"
    },
    {
        id: 21,
        name: "(ANSYS) ANSYS",
        location: "",
        department: "T (all divisions)",
        method: "Simulation Software/FEM"
    },
    {
        id: 22,
        name: "(Ansys) Ansys FEM Software",
        location: "",
        department: "M5 (Tailored Lightweight Composites)",
        method: "Simulation Software/FEM"
    },
    {
        id: 23,
        name: "(Anton Paar) Litesizer 500",
        location: "H012a",
        department: "P2 (Nanostructured Materials)",
        method: "Particle Size Analysis/Dynamic Light Scattering"
    },
    {
        id: 24,
        name: "(Anton Paar) LOVIS 2000 M Microviscometer",
        location: "H13",
        department: "C4 (Advanced Macromolecular Structure Analysis)",
        method: "Viscometry/Solution Viscometry"
    },
    {
        id: 25,
        name: "(Anton Paar) MCR 301",
        location: "H014",
        department: "P3 (Polymer Interfaces)",
        method: "Rheometry/Rotational Rheometer"
    },
    {
        id: 26,
        name: "(Anton Paar) MCR 502",
        location: "H014",
        department: "P3 (Polymer Interfaces)",
        method: "Rheometry/Rotational Rheometer"
    },
    {
        id: 27,
        name: "(Anton Paar) Quantachrome",
        location: "W019",
        department: "P1 (Functional Colloidal Materials)",
        method: "Surface Analysis/Specific Surface Area"
    },
    {
        id: 28,
        name: "(Anton Paar) SurPASS 3",
        location: "H118",
        department: "P3 (Polymer Interfaces)",
        method: "Electrical Characterization/Surface Potential"
    },
    {
        id: 29,
        name: "(Anycubic) M3 Premium",
        location: "B216",
        department: "B (all divisions)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 30,
        name: "(Anycubic) Photon M3 Premium",
        location: "M7",
        department: "M1 (Materials Engineering)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 31,
        name: "(Applied Biosystems) StepOnePlus",
        location: "B112",
        department: "B (all divisions)",
        method: "PCR/Real-Time PCR Detection"
    },
    {
        id: 32,
        name: "(APT GmbH) POLOS SPIN150i",
        location: "H205",
        department: "M1 (Materials Engineering)",
        method: "Coating/Spin coater"
    },
    {
        id: 33,
        name: "(ARBURG) Allrounder 270 S",
        location: "T1",
        department: "M2 (Processing Technology)",
        method: "Injection Molding/Processing Of Small Quantities"
    },
    {
        id: 34,
        name: "(ARBURG) Allrounder 420 C",
        location: "T1",
        department: "M2 (Processing Technology)",
        method: "Injection Molding/Thermoplastic Processing"
    },
    {
        id: 35,
        name: "(ARBURG) Allrounder 470 A 1000 - 170 L2 / 170",
        location: "H11",
        department: "M2 (Processing Technology)",
        method: "Injection Molding/Two-Component Injection Molding (2K)"
    },
    {
        id: 36,
        name: "(ARBURG) freeformer 300-3X",
        location: "H11",
        department: "M2 (Processing Technology)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 37,
        name: "(Arenz) Arex KL1",
        location: "H7",
        department: "M2 (Processing Technology)",
        method: "Glass Spinning/Polymer Spinning System Hybrid Yarn"
    },
    {
        id: 38,
        name: "(Asiga) Max",
        location: "B216",
        department: "B (all divisions)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 39,
        name: "(Asiga) Max (Div. P, #1)",
        location: "W518",
        department: "P2 (Nanostructured Materials)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 40,
        name: "(Asiga) Max (Div. P, #2)",
        location: "W518",
        department: "P2 (Nanostructured Materials)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 41,
        name: "(Asiga) Pico2",
        location: "H013",
        department: "P2 (Nanostructured Materials)",
        method: "Additive Manufacturing/3D Printer"
    },
    {
        id: 42,
        name: "(Asylum Research) MFP3D",
        location: "L01",
        department: "P4 (Multi-Scale Characterization)",
        method: "AFM"
    },
    {
        id: 43,
        name: "(Asylum Research) MFP3D auf Olympus",
        location: "H020",
        department: "P3 (Polymer Interfaces)",
        method: "Combined Microscopy/Atomic Force Microscope"
    },
    {
        id: 44,
        name: "(Asylum Research) MFP3D auf Zeiss 780ZEN",
        location: "H020",
        department: "P3 (Polymer Interfaces)",
        method: "Combined Microscopy/Atomic Force Microscope"
    },
    {
        id: 45,
        name: "(Atlas Materialtesting) HVUL2 Horizontal Vertical Flame Chamber",
        location: "T3",
        department: "M2 (Processing Technology)",
        method: "Fire Testing/Flammability (Ul94)"
    },
    {
        id: 46,
        name: "(BD) LSRFortessa",
        location: "B313",
        department: "B (all divisions)",
        method: "Cell Analysis/Flow Cytometry"
    },
    {
        id: 47,
        name: "(Beckmann Coulter) Coulter AcT diff",
        location: "B211",
        department: "B (all divisions)",
        method: "Blood Analysis/Blood Cell Counter"
    },
    {
        id: 48,
        name: "(Berstorff) ZE25A-48D-UTS-ultra glide Twin screw extruder",
        location: "T1",
        department: "M2 (Processing Technology)",
        method: "Compounding"
    },
    {
        id: 49,
        name: "(Binder) Air circulation drying oven FED 115",
        location: "P4b",
        department: "M2 (Processing Technology)",
        method: "Sample Conditioning /Convection Oven"
    },
    {
        id: 50,
        name: "(Binder) Constant climate chamber KBF 115",
        location: "T015",
        department: "M1 (Materials Engineering)",
        method: "Sample Conditioning /Climate Chamber"
    }
];

// Filter options extracted from real data
const filterOptions = {
    departments: [
        "B (all divisions)",
        "C2 (Bioactive and Responsive Polymers)",
        "C4 (Advanced Macromolecular Structure Analysis)",
        "M1 (Materials Engineering)",
        "M2 (Processing Technology)",
        "M5 (Tailored Lightweight Composites)",
        "P1 (Functional Colloidal Materials)",
        "P2 (Nanostructured Materials)",
        "P3 (Polymer Interfaces)",
        "P4 (Multi-Scale Characterization)",
        "T (all divisions)"
    ],
    methods: [
        "Additive Manufacturing/3D Printer",
        "AFM",
        "Automated Electrophoresis System",
        "Blood Analysis/Blood Cell Counter",
        "Cell Analysis/Flow Cytometry",
        "Chromatography",
        "Chromatography/Size Exclusion Chromatography",
        "Coating/Spin coater",
        "Combined Microscopy/Atomic Force Microscope",
        "Combined Microscopy/Confocal Light Microscope",
        "Combined Microscopy/Light Microscope",
        "Compounding",
        "Electrical Characterization/Surface Potential",
        "Extrusion/Single-Screw Extrusion",
        "Fire Testing/Flammability (Ul94)",
        "Glass Spinning/Polymer Spinning System Hybrid Yarn",
        "Injection Molding/Processing Of Small Quantities",
        "Injection Molding/Thermoplastic Processing",
        "Injection Molding/Two-Component Injection Molding (2K)",
        "Light Microscopy/Confocal",
        "Particle Size Analysis/Dynamic Light Scattering",
        "PCR/Real-Time PCR Detection",
        "Rheometry/Rotational Rheometer",
        "Sample Conditioning /Climate Chamber",
        "Sample Conditioning /Convection Oven",
        "Simulation Software/FEM",
        "Spectroscopy/FT-IR",
        "Spectroscopy/UV-VIS",
        "Surface Analysis/Pore Volume And  Size",
        "Surface Analysis/Specific Surface Area",
        "Twining",
        "UV Treatment/UV-Chamber",
        "Viscometry/Solution Viscometry"
    ]
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { mockResources, filterOptions };
}