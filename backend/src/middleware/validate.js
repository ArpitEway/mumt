export const validate = (schema, source = 'body') => {
  return (req, res, next) => {
    try {
      const dataToValidate = req[source];
      const parsedData = schema.parse(dataToValidate);
      
      // Replace the request data with the parsed data (which handles defaults/coercion)
      req[source] = parsedData;
      
      next();
    } catch (error) {
      const err = new Error("Validation error");
      err.status = 400;
      if (error.errors) {
        const formatted = {};
        error.errors.forEach(e => {
          const path = e.path.join('.');
          if (!formatted[path]) formatted[path] = [];
          formatted[path].push(e.message);
        });
        err.details = formatted;
      } else {
        err.details = error.flatten ? error.flatten().fieldErrors : error;
      }
      next(err);
    }
  };
};
