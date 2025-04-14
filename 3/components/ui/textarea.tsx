export function Textarea(
  props: React.TextareaHTMLAttributes<HTMLTextAreaElement>
) {
  return (
    <textarea
      className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      {...props}
    />
  );
}
