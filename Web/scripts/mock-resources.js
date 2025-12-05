/**
 * Mock data for Resource List View
 * DUMMY DATA - For demonstration purposes only
 * 
 * This file contains fake data to test filtering and sorting functionality.
 * Real data will come from database queries when schema is implemented.
 */

const mockResources = [
    { 
        id: 1, 
        name: "Gadget 1", 
        location: "", 
        department: "M1", 
        method: "Method 1: SIM",
        description: "High-performance simulation software for finite element analysis",
        notes: "Requires advanced training"
    },
    { 
        id: 2, 
        name: "Gadget 2", 
        location: "H224", 
        department: "C4", 
        method: "Method 2: CHROM",
        description: "Automated chromatography system with size exclusion capabilities",
        notes: "Contact lab supervisor before use"
    },
    { id: 3, name: "Gadget 3", location: "H224", department: "C4", method: "Method 2: CHROM" },
    { id: 4, name: "Gadget 4", location: "H224", department: "C4", method: "Method 2: CHROM" },
    { id: 5, name: "Gadget 5", location: "H224", department: "C4", method: "Method 2: CHROM" },
    { id: 6, name: "Gadget 6", location: "B112", department: "B", method: "Method 3: AEP" },
    { id: 7, name: "Gadget 7", location: "P04", department: "C2", method: "Method 4: SPEC" },
    { id: 8, name: "Gadget 8", location: "W218", department: "P1", method: "Method 4: SPEC" },
    { id: 9, name: "Gadget 9", location: "P04", department: "C2", method: "Method 4: SPEC" },
    { id: 10, name: "Gadget 10", location: "H223", department: "C4", method: "Method 2: CHROM" },
    { id: 11, name: "Gadget 11", location: "H224", department: "C4", method: "Method 2: CHROM" },
    { id: 12, name: "Gadget 12", location: "H224", department: "C4", method: "Method 2: CHROM" },
    { id: 13, name: "Gadget 13", location: "H5", department: "M2", method: "Method 5: TWN" },
    { id: 14, name: "Gadget 14", location: "", department: "M2", method: "Method 6: UV" },
    { id: 15, name: "Gadget 15", location: "P011", department: "P2", method: "Method 4: SPEC" },
    { id: 16, name: "Gadget 16", location: "P04", department: "C2", method: "Method 4: SPEC" },
    { id: 17, name: "Gadget 17", location: "H213", department: "C4", method: "Method 7: FTIR" },
    { id: 18, name: "Gadget 18", location: "B313", department: "B", method: "Method 8: LM" },
    { id: 19, name: "Gadget 19", location: "M7", department: "M1", method: "Method 9: AM" },
    { id: 20, name: "Gadget 20", location: "T1", department: "M2", method: "Method 10: EXTR" },
    { id: 21, name: "Gadget 21", location: "", department: "T", method: "Method 1: SIM" },
    { id: 22, name: "Gadget 22", location: "", department: "M5", method: "Method 1: SIM" },
    { id: 23, name: "Gadget 23", location: "H012a", department: "P2", method: "Method 11: PSA" },
    { id: 24, name: "Gadget 24", location: "H13", department: "C4", method: "Method 12: VISC" },
    { id: 25, name: "Gadget 25", location: "H014", department: "P3", method: "Method 13: RHEO" },
    { id: 26, name: "Gadget 26", location: "H014", department: "P3", method: "Method 13: RHEO" },
    { id: 27, name: "Gadget 27", location: "W019", department: "P1", method: "Method 14: SA" },
    { id: 28, name: "Gadget 28", location: "H118", department: "P3", method: "Method 15: EC" },
    { id: 29, name: "Gadget 29", location: "B216", department: "B", method: "Method 9: AM" },
    { id: 30, name: "Gadget 30", location: "M7", department: "M1", method: "Method 9: AM" },
    { id: 31, name: "Gadget 31", location: "B112", department: "B", method: "Method 16: PCR" },
    { id: 32, name: "Gadget 32", location: "H205", department: "M1", method: "Method 17: COAT" },
    { id: 33, name: "Gadget 33", location: "T1", department: "M2", method: "Method 18: INJ" },
    { id: 34, name: "Gadget 34", location: "T1", department: "M2", method: "Method 18: INJ" },
    { id: 35, name: "Gadget 35", location: "H11", department: "M2", method: "Method 19: INJ2" },
    { id: 36, name: "Gadget 36", location: "H11", department: "M2", method: "Method 9: AM" },
    { id: 37, name: "Gadget 37", location: "H7", department: "M2", method: "Method 20: SPIN" },
    { id: 38, name: "Gadget 38", location: "B216", department: "B", method: "Method 9: AM" },
    { id: 39, name: "Gadget 39", location: "W518", department: "P2", method: "Method 9: AM" },
    { id: 40, name: "Gadget 40", location: "W518", department: "P2", method: "Method 9: AM" },
    { id: 41, name: "Gadget 41", location: "H013", department: "P2", method: "Method 9: AM" },
    { id: 42, name: "Gadget 42", location: "L01", department: "P4", method: "Method 21: AFM" },
    { id: 43, name: "Gadget 43", location: "H020", department: "P3", method: "Method 22: CM" },
    { id: 44, name: "Gadget 44", location: "H020", department: "P3", method: "Method 22: CM" },
    { id: 45, name: "Gadget 45", location: "T3", department: "M2", method: "Method 23: FIRE" },
    { id: 46, name: "Gadget 46", location: "B313", department: "B", method: "Method 24: FLOW" },
    { id: 47, name: "Gadget 47", location: "B211", department: "B", method: "Method 25: BLOOD" },
    { id: 48, name: "Gadget 48", location: "T1", department: "M2", method: "Method 26: COMP" },
    { id: 49, name: "Gadget 49", location: "P4b", department: "M2", method: "Method 27: COND" },
    { id: 50, name: "Gadget 50", location: "T015", department: "M1", method: "Method 27: COND" }
];

// Filter options - simplified dummy data
const filterOptions = {
    departments: [
        "B",
        "C2",
        "C4",
        "M1",
        "M2",
        "M5",
        "P1",
        "P2",
        "P3",
        "P4",
        "T"
    ],
    methods: [
        "Method 1: SIM",
        "Method 2: CHROM",
        "Method 3: AEP",
        "Method 4: SPEC",
        "Method 5: TWN",
        "Method 6: UV",
        "Method 7: FTIR",
        "Method 8: LM",
        "Method 9: AM",
        "Method 10: EXTR",
        "Method 11: PSA",
        "Method 12: VISC",
        "Method 13: RHEO",
        "Method 14: SA",
        "Method 15: EC",
        "Method 16: PCR",
        "Method 17: COAT",
        "Method 18: INJ",
        "Method 19: INJ2",
        "Method 20: SPIN",
        "Method 21: AFM",
        "Method 22: CM",
        "Method 23: FIRE",
        "Method 24: FLOW",
        "Method 25: BLOOD",
        "Method 26: COMP",
        "Method 27: COND"
    ]
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { mockResources, filterOptions };
}