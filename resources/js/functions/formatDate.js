import { format } from "date-fns";
import { id } from "date-fns/locale";

const formatDate = (date) => {
    if (!date) return null;
    const parsedDate = new Date(date);
    if (isNaN(parsedDate)) return null;
    return format(parsedDate, "dd MMMM yyyy", { locale: id });
};

export default formatDate;
