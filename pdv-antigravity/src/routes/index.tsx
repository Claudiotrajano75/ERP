import { createFileRoute } from "@tanstack/react-router";
import { Minus, Plus, Search, ShoppingBag, Star, Tag, Trash2, UserRound, UtensilsCrossed } from "lucide-react";
import { useMemo, useState } from "react";

import bife from "@/assets/bife-acebolado.jpg";
import espaguete from "@/assets/espaguete.jpg";
import frango from "@/assets/frango-grelhado.jpg";
import strogonoff from "@/assets/strogonoff.jpg";
import { Button } from "@/components/ui/button";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "PDV Restaurante | Mesa" },
      { name: "description", content: "PDV profissional e responsivo para operações de restaurante." },
      { property: "og:title", content: "PDV Restaurante | Mesa" },
      { property: "og:description", content: "PDV profissional e responsivo para operações de restaurante." },
      { property: "og:type", content: "website" },
      { name: "twitter:card", content: "summary_large_image" },
    ],
  }),
  component: Index,
});

type Product = { id: number; name: string; category: string; price: number; image: string };
type CartLine = Product & { quantity: number };

const products: Product[] = [
  { id: 1, name: "Frango grelhado", category: "Executivos", price: 24.9, image: frango },
  { id: 2, name: "Bife acebolado", category: "Executivos", price: 32.9, image: bife },
  { id: 3, name: "Strogonoff de frango", category: "Executivos", price: 27.9, image: strogonoff },
  { id: 4, name: "Espaguete à bolonhesa", category: "Massas", price: 26.9, image: espaguete },
  { id: 5, name: "Frango com legumes", category: "Favoritos", price: 29.9, image: frango },
  { id: 6, name: "Carne de sol", category: "Favoritos", price: 34.9, image: bife },
  { id: 7, name: "Strogonoff especial", category: "Promoções", price: 31.9, image: strogonoff },
  { id: 8, name: "Massa da casa", category: "Massas", price: 28.9, image: espaguete },
];

const currency = new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" });

function Index() {
  const [activeCategory, setActiveCategory] = useState("Todos");
  const [search, setSearch] = useState("");
  const [mobileView, setMobileView] = useState<"catalog" | "cart">("catalog");
  const [cart, setCart] = useState<CartLine[]>(() =>
    products.slice(0, 2).map((product) => ({ ...product, quantity: 1 })),
  );

  const visibleProducts = products.filter((product) => {
    const categoryMatch = activeCategory === "Todos" || product.category === activeCategory;
    return categoryMatch && product.name.toLowerCase().includes(search.toLowerCase());
  });
  const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = useMemo(() => cart.reduce((sum, item) => sum + item.price * item.quantity, 0), [cart]);

  const addProduct = (product: Product) => {
    setCart((current) => {
      const existing = current.find((item) => item.id === product.id);
      return existing
        ? current.map((item) => item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item)
        : [...current, { ...product, quantity: 1 }];
    });
  };

  const updateQuantity = (id: number, delta: number) => {
    setCart((current) => current
      .map((item) => item.id === id ? { ...item, quantity: item.quantity + delta } : item)
      .filter((item) => item.quantity > 0));
  };

  return (
    <main className="flex h-dvh min-h-[560px] flex-col overflow-hidden bg-background text-foreground">
      <header className="grid h-16 shrink-0 grid-cols-[minmax(0,1fr)_auto] items-center border-b border-border bg-card px-4 sm:px-5">
        <div className="flex min-w-0 items-center gap-3">
          <div className="grid size-9 shrink-0 place-items-center rounded-lg bg-foreground text-background"><UtensilsCrossed size={17} /></div>
          <div className="min-w-0">
            <h1 className="truncate font-display text-base font-bold">Mesa <span className="text-primary">PDV</span></h1>
            <p className="truncate text-[11px] font-semibold text-muted-foreground">Caixa 02 · Turno da tarde</p>
          </div>
        </div>
        <div className="flex items-center gap-2">
          <span className="hidden items-center gap-2 text-xs font-semibold text-muted-foreground sm:flex"><span className="size-2 rounded-full bg-success" /> Online</span>
          <Button variant="quiet" size="sm" className="hidden md:inline-flex"><UserRound size={15} /> Marina Costa</Button>
        </div>
      </header>

      <div className="grid min-h-0 flex-1 grid-cols-1 md:grid-cols-[minmax(0,1fr)_340px] xl:grid-cols-[minmax(0,1fr)_400px]">
        <section className={`${mobileView === "cart" ? "hidden" : "flex"} min-h-0 min-w-0 flex-col border-r border-border md:flex`}>
          <div className="shrink-0 space-y-3 border-b border-border bg-card p-3 sm:p-4">
            <div className="grid grid-cols-[minmax(0,1fr)_auto] gap-2">
              <label className="relative min-w-0">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" size={18} />
                <input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Buscar produto ou código..." className="h-11 w-full rounded-lg border border-input bg-background pl-10 pr-3 text-sm outline-none transition-colors placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/15" />
              </label>
              <div className="hidden gap-2 sm:flex">
                <Button variant="quiet"><Star size={16} /> Favoritos</Button>
                <Button variant="quiet"><Tag size={16} /> Promoções</Button>
              </div>
            </div>
            <div className="scrollbar-thin flex gap-2 overflow-x-auto pb-1">
              {["Todos", "Executivos", "Massas", "Favoritos", "Promoções"].map((category) => (
                <Button key={category} size="sm" variant={activeCategory === category ? "primary" : "secondary"} onClick={() => setActiveCategory(category)}>{category}</Button>
              ))}
            </div>
          </div>

          <div className="scrollbar-thin min-h-0 flex-1 overflow-y-auto p-3 sm:p-4">
            <div className="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
              {visibleProducts.map((product) => (
                <button key={product.id} onClick={() => addProduct(product)} className="group min-w-0 overflow-hidden rounded-lg border border-border bg-card text-left shadow-sm transition-[border-color,box-shadow,transform] hover:-translate-y-0.5 hover:border-primary hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                  <img src={product.image} alt={product.name} loading="lazy" width={944} height={704} className="aspect-[4/3] w-full object-cover transition-transform duration-300 group-hover:scale-[1.025]" />
                  <div className="p-3">
                    <span className="text-[10px] font-bold uppercase text-primary">{product.category}</span>
                    <h2 className="mt-1 line-clamp-2 min-h-10 font-display text-sm font-semibold leading-5">{product.name}</h2>
                    <p className="mt-2 text-base font-extrabold">{currency.format(product.price)}</p>
                  </div>
                </button>
              ))}
            </div>
          </div>
        </section>

        <aside className={`${mobileView === "catalog" ? "hidden" : "flex"} min-h-0 flex-col bg-card md:flex`}>
          <div className="grid shrink-0 grid-cols-2 gap-2 border-b border-border p-3 sm:p-4">
            <button className="min-w-0 rounded-lg border border-border bg-secondary p-3 text-left"><span className="block text-[10px] font-bold uppercase text-muted-foreground">Cliente</span><strong className="block truncate text-sm">Consumidor final</strong></button>
            <button className="min-w-0 rounded-lg border border-border bg-secondary p-3 text-left"><span className="block text-[10px] font-bold uppercase text-muted-foreground">Vendedor</span><strong className="block truncate text-sm">Marina Costa</strong></button>
          </div>

          <div className="scrollbar-thin min-h-0 flex-1 overflow-y-auto px-4">
            <div className="flex items-center justify-between border-b border-border py-3"><h2 className="font-display text-sm font-bold">Pedido atual</h2><span className="text-xs font-bold text-muted-foreground">{itemCount} {itemCount === 1 ? "item" : "itens"}</span></div>
            {cart.length === 0 ? <div className="grid h-40 place-items-center text-center text-sm text-muted-foreground">Seu pedido está vazio</div> : cart.map((item) => (
              <div key={item.id} className="grid grid-cols-[minmax(0,1fr)_auto] gap-3 border-b border-border py-4">
                <div className="min-w-0"><p className="truncate text-sm font-bold">{item.name}</p><p className="mt-1 text-xs text-muted-foreground">{currency.format(item.price)} cada</p></div>
                <div className="flex items-center gap-2">
                  <Button variant="quiet" size="icon" aria-label={`Diminuir ${item.name}`} onClick={() => updateQuantity(item.id, -1)}>{item.quantity === 1 ? <Trash2 size={14} /> : <Minus size={14} />}</Button>
                  <strong className="w-5 text-center text-sm">{item.quantity}</strong>
                  <Button variant="quiet" size="icon" aria-label={`Aumentar ${item.name}`} onClick={() => updateQuantity(item.id, 1)}><Plus size={14} /></Button>
                </div>
              </div>
            ))}
          </div>

          <div className="shrink-0 border-t border-border bg-background p-4">
            <div className="grid grid-cols-2 gap-2">
              <button className="rounded-lg border border-border bg-card p-3 text-left"><span className="block text-[10px] font-bold uppercase text-muted-foreground">Desconto</span><strong className="text-sm">R$ 0,00</strong></button>
              <button className="rounded-lg border border-border bg-card p-3 text-left"><span className="block text-[10px] font-bold uppercase text-muted-foreground">Acréscimo</span><strong className="text-sm">R$ 0,00</strong></button>
            </div>
            <div className="flex items-end justify-between py-4"><span className="text-xs font-bold uppercase text-muted-foreground">Total a pagar</span><strong className="font-display text-3xl">{currency.format(subtotal)}</strong></div>
            <div className="grid grid-cols-2 gap-2"><Button variant="outline">Suspender</Button><Button variant="destructive">Cancelar</Button></div>
            <Button size="lg" className="mt-2 w-full text-base"><ShoppingBag size={19} /> Finalizar venda</Button>
          </div>
        </aside>
      </div>

      <nav className="grid h-16 shrink-0 grid-cols-2 border-t border-border bg-card p-2 md:hidden" aria-label="Navegação do PDV">
        <Button variant={mobileView === "catalog" ? "primary" : "ghost"} onClick={() => setMobileView("catalog")}><UtensilsCrossed size={17} /> Produtos</Button>
        <Button variant={mobileView === "cart" ? "primary" : "ghost"} onClick={() => setMobileView("cart")}><ShoppingBag size={17} /> Pedido ({itemCount})</Button>
      </nav>
    </main>
  );
}