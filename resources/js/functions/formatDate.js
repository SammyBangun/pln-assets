import { format } from "date-fns";
import { id } from "date-fns/locale";

const formatDate = (date) => {
    return format(new Date(date), "dd MMMM yyyy", { locale: id });
};

export default formatDate;
