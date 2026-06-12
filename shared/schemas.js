import { z } from "zod";

// --- Auth Schemas ---
export const loginSchema = z.object({
  role: z.enum(['admin', 'center', 'student', 'teacher', 'exam_center']).optional(),
  username: z.string().min(1, "Username is required").optional(),
  password: z.string().min(1, "Password is required").optional(),
  // enrollmentNo: z.string().optional(),
  // dob: z.string().optional(),
  nonEnrolled: z.boolean().optional()
});

export const adminLoginSchema = loginSchema.extend({ role: z.literal('admin') });

export const changePasswordSchema = z.object({
  currentPassword: z.string().min(1, "Current password is required"),
  newPassword: z.string().min(1, "New password is required")
});

// --- Registration Schemas ---
export const registerSchema = z.object({
  fullName: z.string().min(3, "Full name is required").max(255),
  dob: z.string().regex(/^\d{2}\/\d{2}\/\d{4}$/, "Date must be in DD/MM/YYYY format"),
  mobile: z.string().regex(/^\d{10}$/, "Mobile must be 10 digits"),
  fatherName: z.string().min(3, "Father/Husband name is required").max(255),
  email: z.string().email("Invalid email"),
  aadharNo: z.string().regex(/^\d{12}$/, "Aadhaar number must be 12 digits"),
  eligibility: z.string().min(1, 'Eligibility is required'),
  course: z.string().min(1, "Course is required")
});

export const studentRegistrationSchema = z.object({
  session: z.string().min(1, 'Session is required'),
  course: z.string().min(1, 'Course is required'),
  eligibility: z.string().min(1, 'Eligibility is required'),
  class: z.string().min(1, 'Class is required'),
  fullName: z.string().min(3, 'Full name is required'),
  fatherName: z.string().min(3, 'Father/Husband name is required'),
  motherName: z.string().min(3, 'Mother name is required'),
  gender: z.string().min(1, 'Gender is required'),
  medium: z.string().optional(),
  maritalStatus: z.string().optional(),
  phoneNumber: z.string().regex(/^\d{10}$/, 'Phone number must be 10 digits'),
  email: z.string().email('Invalid email'),
  dateOfBirth: z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Date of Birth is required'),
  nationality: z.string().min(1, 'Nationality is required'),
  religion: z.string().min(1, 'Religion is required'),
  category: z.string().min(1, 'Category is required'),
  minority: z.string().optional(),
  disabilityStatus: z.string().optional(),
  aadharNumber: z.union([z.string().regex(/^\d{12}$/, 'Aadhaar number must be 12 digits'), z.literal('')]).optional(),
  currentAddress: z.string().min(1, 'Current address is required'),
  currentState: z.string().min(1, 'Current state is required'),
  currentDistrict: z.string().min(1, 'Current district is required'),
  currentCity: z.string().min(1, 'Current city is required'),
  currentPinCode: z.string().regex(/^\d{6}$/, 'Current PIN code must be 6 digits'),
  permanentAddress: z.string().min(1, 'Permanent address is required'),
  permanentState: z.string().min(1, 'Permanent state is required'),
  permanentDistrict: z.string().min(1, 'Permanent district is required'),
  permanentCity: z.string().min(1, 'Permanent city is required'),
  permanentPinCode: z.string().regex(/^\d{6}$/, 'Permanent PIN code must be 6 digits'),
  photo: z.any().optional(),
});

// --- Resources Schemas ---
export const listSchema = z.object({
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(100).default(20),
  q: z.string().optional(),
  sortBy: z.string().regex(/^[a-zA-Z0-9_]+$/).default('id'),
  sortDir: z.enum(['asc', 'desc']).default('desc')
});

// --- Student Form Schemas ---
export const updateStudentSchema = z.object({
  session: z.string().min(1,"Session is required"),
  course_category: z.string().min(1,"Course category is required"),
  course_name: z.string().min(1,"Course name is required"),
  class_name: z.string().min(1,"Class name is required"),
  course_duration: z.string().min(1,"Course duration is required"),
  mode: z.string().min(1,"Mode is required"),
  eligibility: z.string().min(1,"Eligibility is required"),
  exam_year: z.string().min(1,"Exam year is required"),
  name: z.string().min(1,"Name is required"),
  name_hindi: z.string().min(1,"Name Hindi is required"),
  f_h_name: z.string().min(1,"Father/Husband name is required"),
  f_h_name_hindi: z.string().min(1,"Father/Husband name Hindi is required"),
  mother_name: z.string().min(1,"Mother name is required"),
  mother_name_hindi: z.string().min(1,"Mother name Hindi is required"),
  gender: z.string().min(1,"Gender is required"),
  dob: z.string().min(1,"DOB is required"),
  category: z.string().min(1,"Category is required"),
  permanent_resident: z.string().min(1,"Permanent resident status is required"),
  adhar_no: z.string().min(1,"Aadhaar number is required"),
  adhar_name: z.string().min(1,"Aadhaar name is required"),
  adhar_dob: z.string().min(1,"Aadhaar DOB is required"),
  aadhaar_verifed: z.string().min(1,"Aadhaar verified status is required"),
  sm_id: z.string().min(1,"SM ID is required"),
}).partial();

export const updateStudentDataSchema = z.object({
  // p_mobile_no: z.string().min(1,"Primary mobile number is required"),
  // p_email: z.string().email().min(1,"Primary email is required"),
  nationality: z.string().min(1,"Nationality is required"),
  religion: z.string().min(1,"Religion is required"),
  f_h_occupation: z.string().min(1,"Father/Husband occupation is required"),
  mother_occupation: z.string().min(1,"Mother occupation is required"),
  p_handicapped: z.string().min(1,"Physical handicap info is required"),
  c_address: z.string().min(1,"Current address is required"),
  c_city: z.string().min(1,"Current city is required"),
  c_district: z.string().min(1,"Current district is required"),
  c_state: z.string().min(1,"Current state is required"),
  c_pin_code: z.string().min(1,"Current PIN code is required"),
  f_h_mobile_no: z.string().min(1,"Father/Husband mobile is required"),
  p_address: z.string().min(1,"Permanent address is required"),
  p_city: z.string().min(1,"Permanent city is required"),
  p_district: z.string().min(1,"Permanent district is required"),
  p_state: z.string().min(1,"Permanent state is required"),
  p_pin_code: z.string().min(1,"Permanent PIN code is required"),
  ten_board: z.string().min(1,"10th board is required"),
  ten_total_marks: z.string().min(1,"10th total marks is required"),
  ten_marks: z.string().min(1,"10th marks is required"),
  ten_subjects: z.string().min(1,"10th subjects is required"),
  ten_year: z.string().min(1,"10th year is required"),
  twowelth_board: z.string().min(1,"12th board is required"),
  twowelth_total_marks: z.string().min(1,"12th total marks is required"),
  twowelth_marks: z.string().min(1,"12th marks is required"),
  twowelth_subject: z.string().min(1,"12th subject is required"),
  twowelth_year: z.string().min(1,"12th year is required"),
  graduation_university: z.string().min(1,"Graduation university is required"),
  graduation_total_marks: z.string().min(1,"Graduation total marks is required"),
  graduation_marks: z.string().min(1,"Graduation marks is required"),
  graduation_subject: z.string().min(1,"Graduation subject is required"),
  graduation_year: z.string().min(1,"Graduation year is required"),
  pg_university: z.string().min(1,"Post‑grad university is required"),
  pg_total_marks: z.string().min(1,"Post‑grad total marks is required"),
  pg_marks: z.string().min(1,"Post‑grad marks is required"),
  pg_subject: z.string().min(1,"Post‑grad subject is required"),
  pg_year: z.string().min(1,"Post‑grad year is required"),
  blood_group: z.string().min(1,"Blood group is required"),
  guardian_mob: z.string().min(1,"Guardian mobile is required"),
  samagra_id: z.string().min(1,"Samagra ID is required"),
}).partial();

export const studentFormSchema = z.object({
  student: updateStudentSchema,
  student_data: updateStudentDataSchema
});
