export type PostcodeData = {
id: number;
postcode: string;
place_name: string;
county: string | null;
country: string | null;
type: string | null;
latitude: number;
longitude: number;
address: string;
};
export type StoreData = {
id: number;
name: string;
  delivery_radius_km: number;
  postcode: PostcodeData;
  distance_km: number | null;
};
