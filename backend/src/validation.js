import { z } from "zod";

export const schemas = {
  register: z.object({
    fullName: z.string().min(1, "Full name is required"),
    dob: z.string().refine(val => !isNaN(Date.parse(val)), "Invalid date"),
    mobile: z.string().regex(/^\d{10}$/, "Mobile must be 10 digits"),
    fatherName: z.string().optional(),
    email: z.string().email("Invalid email"),
    mode: z.string().optional(),
    category: z.string().optional(),
    course: z.string().optional()
  })
};

export const validate = (schema, data) => {
  const result = schema.safeParse(data);
  if (!result.success) {
    const err = new Error("Validation error");
    err.status = 400;
    err.details = result.error.errors;
    throw err;
  }
  return result.data;
};
