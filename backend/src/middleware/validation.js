import { validate as zodValidate } from './validate.js';

/**
 * validation middleware – thin wrapper around existing Zod validate helper.
 * Allows usage as `validation(schema, 'body')`.
 */
export const validation = (schema, source = 'body') => zodValidate(schema, source);
