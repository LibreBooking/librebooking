/**
 * Mock data for Resource List View
 * Based on real Device Hub CSV export
 * 
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
    }
];

// Filter options extracted from CSV data
const filterOptions = {
    departments: [
        "B (all divisions)",
        "C2 (Bioactive and Responsive Polymers)",
        "C4 (Advanced Macromolecular Structure Analysis)",
        "M1 (Materials Engineering)",
        "M2 (Processing Technology)",
        "P1 (Functional Colloidal Materials)",
        "P2 (Nanostructured Materials)"
    ],
    methods: [
        "Additive Manufacturing/3D Printer",
        "Automated Electrophoresis System",
        "Chromatography",
        "Chromatography/Size Exclusion Chromatography",
        "Extrusion/Single-Screw Extrusion",
        "Light Microscopy/Confocal",
        "Simulation Software/FEM",
        "Spectroscopy/FT-IR",
        "Spectroscopy/UV-VIS",
        "Twining",
        "UV Treatment/UV-Chamber"
    ]
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { mockResources, filterOptions };
}